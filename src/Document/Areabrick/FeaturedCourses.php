<?php

declare(strict_types=1);

namespace App\Document\Areabrick;

use App\Course\CourseRepository;
use OpenDxp\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use OpenDxp\Extension\Document\Areabrick\Attribute\AsAreabrick;
use OpenDxp\Model\Document\Editable\Area\Info;
use Symfony\Component\HttpFoundation\Response;

/**
 * The courses an editor marked as featured. Editors maintain the list in the course objects, not in the page.
 */
#[AsAreabrick(id: 'featured-courses')]
final class FeaturedCourses extends AbstractTemplateAreabrick
{
    private const int LIMIT = 3;

    public function __construct(private readonly CourseRepository $courses)
    {
    }

    public function getName(): string
    {
        return 'Kurse im Fokus';
    }

    public function getDescription(): string
    {
        return 'Die Kurse mit dem Häkchen „Auf der Startseite hervorheben“.';
    }

    public function getIcon(): string
    {
        return '/bundles/opendxpadmin/img/flat-color-icons/graduation_cap.svg';
    }

    public function action(Info $info): ?Response
    {
        $info->setParam('courses', $this->courses->findFeatured(self::LIMIT));

        return null;
    }
}
