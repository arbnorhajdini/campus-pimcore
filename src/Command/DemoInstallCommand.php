<?php

declare(strict_types=1);

namespace App\Command;

use App\Controller\CourseController;
use App\Controller\DefaultController;
use App\Controller\RegistrationController;
use App\Demo\DemoImageGenerator;
use App\Registration\RegistrationRecorder;
use Carbon\Carbon;
use OpenDxp\Model\Asset;
use OpenDxp\Model\DataObject;
use OpenDxp\Model\DataObject\Course;
use OpenDxp\Model\DataObject\Location;
use OpenDxp\Model\Document;
use OpenDxp\Model\WebsiteSetting;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Yaml\Yaml;

/**
 * Creates the demo content from fixtures/demo.yaml. Running it again updates the content instead of duplicating it.
 */
#[AsCommand(name: 'app:demo:install', description: 'Creates the demo pages, locations, courses and images')]
final class DemoInstallCommand extends Command
{
    private const array LANGUAGES = ['de', 'fr'];

    private const string HOME_CONTROLLER = DefaultController::class . '::defaultAction';

    /** The pages below the start page of every language, with the website setting that points to them. */
    private const array SUBPAGES = [
        'courses' => ['controller' => CourseController::class . '::listAction', 'setting' => 'coursesPage'],
        'registration' => ['controller' => RegistrationController::class . '::formAction', 'setting' => 'registrationPage'],
    ];

    public function __construct(
        private readonly DemoImageGenerator $images,
        #[Autowire('%kernel.project_dir%/fixtures/demo.yaml')]
        private readonly string $fixtureFile,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var array{pages: array<string, array<string, array<string, mixed>>>, locations: array<string, array<string, string>>, courses: array<string, array<string, mixed>>} $fixtures */
        $fixtures = Yaml::parseFile($this->fixtureFile);

        $this->installPages($fixtures['pages']);
        $io->writeln(sprintf('%d languages with pages', count($fixtures['pages'])));

        $locations = $this->installLocations($fixtures['locations']);
        $io->writeln(sprintf('%d locations', count($locations)));

        $courses = $this->installCourses($fixtures['courses'], $locations);
        $io->writeln(sprintf('%d courses', count($courses)));

        DataObject\Service::createFolderByPath(RegistrationRecorder::FOLDER);

        $io->success('Demo content installed.');

        return Command::SUCCESS;
    }

    /**
     * Creates /de and /fr with their subpages, links them as translations
     * and stores every subpage per language as a website setting, like coursesPage.
     *
     * @param array<string, array<string, array<string, mixed>>> $fixtures
     */
    private function installPages(array $fixtures): void
    {
        $root = Document\Page::getById(1) ?? throw new RuntimeException('The root document is missing.');
        $root->setController(DefaultController::class . '::rootAction');
        $root->save();

        $documentService = new Document\Service();
        $sources = [];

        foreach ($fixtures as $language => $pages) {
            $home = $this->installPage($root, $language, $pages['home'], self::HOME_CONTROLLER);
            $installed = ['home' => $home];

            foreach (self::SUBPAGES as $name => $subpage) {
                $installed[$name] = $this->installPage($home, $pages[$name]['key'], $pages[$name], $subpage['controller']);
                $this->installWebsiteSetting($subpage['setting'], $installed[$name], $language);
            }

            // The bricks link to the subpages, so they are filled once those exist.
            $this->setEditables($home, $this->brickEditables('content', $pages['home']['bricks']));
            $home->setProperty('language', 'text', $language, false, true);
            $home->save();

            // The first language is the source, every other language is linked to it.
            if ($sources === []) {
                $sources = $installed;

                continue;
            }
            foreach ($installed as $name => $document) {
                $documentService->addTranslation($sources[$name], $document, $language);
            }
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function installPage(Document $parent, string $key, array $data, string $controller): Document\Page
    {
        $path = rtrim($parent->getRealFullPath(), '/') . '/' . $key;
        $page = Document\Page::getByPath($path) ?? new Document\Page();
        $page->setParent($parent);
        $page->setKey($key);
        $page->setController($controller);
        $page->setTitle($data['title']);
        $page->setDescription($data['description']);
        $this->setEditables($page, $data['editables'] ?? []);

        if (isset($data['navigation'])) {
            $page->setProperty('navigation_name', 'text', $data['navigation']);
        }

        $page->setPublished(true);
        $page->save();

        return $page;
    }

    /**
     * Replaces all editables of the page, so editables from an earlier run do not stay behind.
     *
     * @param array<string, array{type: string, data: mixed}> $editables
     */
    private function setEditables(Document\Page $page, array $editables): void
    {
        $page->setEditables([]);

        foreach ($editables as $name => $editable) {
            $data = $editable['data'];
            if ($editable['type'] === 'image') {
                $data = ['id' => $this->installImage('/Seiten', $data['asset'], 'business')->getId()];
            }

            $page->setRawEditable($name, $editable['type'], $data);
        }
    }

    /**
     * Turns the bricks of the fixtures into the flat list of editables that OpenDXP stores.
     * A brick editable is named "content:2.headline", an entry of a block inside it "content:4.items:1.question".
     *
     * @param list<array<string, mixed>> $bricks
     *
     * @return array<string, array{type: string, data: mixed}>
     */
    private function brickEditables(string $areablock, array $bricks): array
    {
        $indices = [];
        $editables = [];

        foreach ($bricks as $position => $brick) {
            $key = (string) ($position + 1);
            $indices[] = ['key' => $key, 'type' => $brick['type'], 'hidden' => false];

            foreach ($brick['editables'] ?? [] as $name => $editable) {
                $editables["{$areablock}:{$key}.{$name}"] = $editable;
            }

            foreach ($brick['blocks'] ?? [] as $block => $entries) {
                $editables["{$areablock}:{$key}.{$block}"] = ['type' => 'block', 'data' => range(1, count($entries))];

                foreach ($entries as $index => $entry) {
                    foreach ($entry as $name => $editable) {
                        $editables[sprintf('%s:%s.%s:%d.%s', $areablock, $key, $block, $index + 1, $name)] = $editable;
                    }
                }
            }
        }

        return [$areablock => ['type' => 'areablock', 'data' => $indices], ...$editables];
    }

    private function installWebsiteSetting(string $name, Document $document, string $language): void
    {
        $setting = WebsiteSetting::getByName($name, null, $language) ?? new WebsiteSetting();
        $setting->setName($name);
        $setting->setLanguage($language);
        $setting->setData($document);
        $setting->save();
    }

    /**
     * @param array<string, array<string, string>> $fixtures
     *
     * @return array<string, Location>
     */
    private function installLocations(array $fixtures): array
    {
        $folder = DataObject\Service::createFolderByPath('/Standorte');
        $locations = [];

        foreach ($fixtures as $key => $data) {
            $location = Location::getByPath("/Standorte/{$key}") ?? new Location();
            $location->setParent($folder);
            $location->setKey($key);
            $location->setName($data['name']);
            $location->setCity($data['city']);
            $location->setAddress($data['address']);
            $location->setPublished(true);
            $location->save();

            $locations[$key] = $location;
        }

        return $locations;
    }

    /**
     * @param array<string, array<string, mixed>> $fixtures
     * @param array<string, Location> $locations
     *
     * @return list<Course>
     */
    private function installCourses(array $fixtures, array $locations): array
    {
        $folder = DataObject\Service::createFolderByPath('/Kurse');
        $courses = [];

        foreach ($fixtures as $slug => $data) {
            $course = Course::getByPath("/Kurse/{$slug}") ?? new Course();
            $course->setParent($folder);
            $course->setKey($slug);
            $course->setSlug($slug);
            $course->setCategory($data['category']);
            $course->setFormat($data['format']);
            $course->setDurationSemesters($data['durationSemesters']);
            $course->setPrice($data['price']);
            $course->setStartDate(Carbon::parse($data['start']));
            $course->setFeatured($data['featured']);
            $course->setLocations(array_map(fn (string $key) => $locations[$key], $data['locations']));
            $course->setImage($this->installImage('/Kurse', $slug, $data['category']));

            foreach (self::LANGUAGES as $language) {
                $course->setTitle($data[$language]['title'], $language);
                $course->setTeaser($data[$language]['teaser'], $language);
                $course->setDescription(trim($data[$language]['description']), $language);
            }

            $course->setPublished(true);
            $course->save();

            $courses[] = $course;
        }

        return $courses;
    }

    private function installImage(string $folderPath, string $name, string $category): Asset\Image
    {
        $filename = "{$name}.jpg";
        $existing = Asset\Image::getByPath("{$folderPath}/{$filename}");
        if ($existing instanceof Asset\Image) {
            return $existing;
        }

        $folder = Asset\Service::createFolderByPath($folderPath);
        $image = new Asset\Image();
        $image->setParent($folder);
        $image->setFilename($filename);
        $image->setData($this->images->generate($category, $name));
        $image->save();

        return $image;
    }
}
