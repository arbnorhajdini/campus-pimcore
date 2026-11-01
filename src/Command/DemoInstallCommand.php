<?php

declare(strict_types=1);

namespace App\Command;

use App\Controller\CourseController;
use App\Controller\DefaultController;
use App\Demo\DemoImageGenerator;
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

        DataObject\Service::createFolderByPath('/Anmeldungen');

        $io->success('Demo content installed.');

        return Command::SUCCESS;
    }

    /**
     * Creates /de and /fr with their course list, links them as translations
     * and stores the course list per language as the website setting coursesPage.
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
            $home = $this->installPage($root, $language, $pages['home'], DefaultController::class . '::homeAction');
            $home->setProperty('language', 'text', $language, false, true);
            $home->save();

            $courses = $this->installPage($home, $pages['courses']['key'], $pages['courses'], CourseController::class . '::listAction');
            $this->installWebsiteSetting('coursesPage', $courses, $language);

            // The first language is the source, every other language is linked to it.
            if ($sources === []) {
                $sources = ['home' => $home, 'courses' => $courses];
            } else {
                $documentService->addTranslation($sources['home'], $home, $language);
                $documentService->addTranslation($sources['courses'], $courses, $language);
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

        foreach ($data['editables'] as $name => $editable) {
            $page->setRawEditable($name, $editable['type'], $editable['data']);
        }

        if (isset($data['navigation'])) {
            $page->setProperty('navigation_name', 'text', $data['navigation']);
        }

        $page->setPublished(true);
        $page->save();

        return $page;
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
            $course->setImage($this->installImage($slug, $data['category']));

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

    private function installImage(string $slug, string $category): Asset\Image
    {
        $filename = "{$slug}.jpg";
        $existing = Asset\Image::getByPath("/Kurse/{$filename}");
        if ($existing instanceof Asset\Image) {
            return $existing;
        }

        $folder = Asset\Service::createFolderByPath('/Kurse');
        $image = new Asset\Image();
        $image->setParent($folder);
        $image->setFilename($filename);
        $image->setData($this->images->generate($category, $slug));
        $image->save();

        return $image;
    }
}
