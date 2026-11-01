<?php

declare(strict_types=1);

namespace App\Controller;

use OpenDxp\Controller\FrontendController;
use OpenDxp\Tool;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends FrontendController
{
    /**
     * The root document has no content. It sends the visitor to the language they prefer.
     * The answer depends on Accept-Language, so caches must keep one per language.
     */
    public function rootAction(Request $request): RedirectResponse
    {
        $language = $request->getPreferredLanguage(Tool::getValidLanguages()) ?? Tool::getDefaultLanguage();

        $response = $this->redirect("/{$language}");
        $response->setVary('Accept-Language');

        return $response;
    }

    /**
     * A page built from bricks. OpenDXP uses this action for every page an editor creates in the admin,
     * the start pages /de and /fr included.
     */
    public function defaultAction(): Response
    {
        return $this->render('pages/default.html.twig');
    }
}
