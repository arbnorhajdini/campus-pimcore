<?php

declare(strict_types=1);

namespace App\Controller;

use App\Course\CourseRepository;
use OpenDxp\Bundle\AdminBundle\Controller\Admin\LoginController;
use OpenDxp\Controller\FrontendController;
use OpenDxp\Tool;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends FrontendController
{
    /**
     * The root document has no content. It sends the visitor to the language they prefer.
     */
    public function rootAction(Request $request): RedirectResponse
    {
        $language = $request->getPreferredLanguage(Tool::getValidLanguages()) ?? Tool::getDefaultLanguage();

        return $this->redirect("/{$language}");
    }

    public function homeAction(CourseRepository $courses): Response
    {
        return $this->render('pages/home.html.twig', [
            'featuredCourses' => $courses->findFeatured(3),
        ]);
    }

    public function loginAction(): Response
    {
        return $this->forward(LoginController::class.'::loginCheckAction');
    }
}
