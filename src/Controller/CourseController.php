<?php

declare(strict_types=1);

namespace App\Controller;

use App\Course\Category;
use App\Course\CourseFilter;
use App\Course\CourseRepository;
use OpenDxp\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CourseController extends FrontendController
{
    public function __construct(private readonly CourseRepository $courses)
    {
    }

    /**
     * Renders the document /de/kurse and /fr/cours. Editors maintain the intro, the code adds the list.
     */
    public function listAction(Request $request): Response
    {
        $filter = CourseFilter::fromRequest($request);

        return $this->render('course/list.html.twig', [
            'courses' => $this->courses->findByFilter($filter),
            'filter' => $filter,
            'categories' => Category::cases(),
            'locations' => $this->courses->findLocations(),
        ]);
    }

    #[Route(path: ['de' => '/de/kurse/{slug}', 'fr' => '/fr/cours/{slug}'], name: 'course_detail', requirements: ['slug' => '[a-z0-9-]+'])]
    public function detailAction(string $slug): Response
    {
        $course = $this->courses->findBySlug($slug);
        if ($course === null) {
            throw $this->createNotFoundException("Course {$slug} not found.");
        }

        return $this->render('course/detail.html.twig', [
            'course' => $course,
        ]);
    }
}
