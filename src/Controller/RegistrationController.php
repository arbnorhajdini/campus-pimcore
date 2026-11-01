<?php

declare(strict_types=1);

namespace App\Controller;

use App\Course\CourseRepository;
use App\Form\RegistrationType;
use App\Registration\RegistrationData;
use App\Registration\RegistrationRecorder;
use OpenDxp\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends FrontendController
{
    /**
     * Renders the document /de/anmeldung and /fr/inscription.
     * After a successful registration it redirects, so a reload does not send the form again.
     */
    public function formAction(Request $request, CourseRepository $courses, RegistrationRecorder $recorder): Response
    {
        $data = new RegistrationData();
        $data->course = $courses->findBySlug($request->query->getString('course'));

        $form = $this->createForm(RegistrationType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recorder->record($data, $request->getLocale());
            $this->addFlash('registration', (string) $data->course?->getTitle());

            return $this->redirect($request->getPathInfo());
        }

        // Symfony answers 422 when the submitted form is invalid.
        return $this->render('registration/form.html.twig', ['form' => $form]);
    }
}
