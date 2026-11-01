<?php

declare(strict_types=1);

namespace App\Form;

use App\Course\CourseFilter;
use App\Course\CourseRepository;
use App\Registration\RegistrationData;
use Collator;
use OpenDxp\Model\DataObject\Course;
use OpenDxp\Model\DataObject\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @extends AbstractType<RegistrationData>
 */
final class RegistrationType extends AbstractType
{
    public function __construct(
        private readonly CourseRepository $courses,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // A select is searched by name, so the courses are sorted by title in the language of the page.
        $courses = $this->courses->findByFilter(new CourseFilter());
        $collator = new Collator($this->requestStack->getCurrentRequest()?->getLocale() ?? 'de');
        usort($courses, static fn (Course $a, Course $b) => $collator->compare((string) $a->getTitle(), (string) $b->getTitle()));

        $builder
            ->add('course', ChoiceType::class, [
                'label' => 'registration.course',
                'placeholder' => 'registration.choose_course',
                'attr' => ['data-course-select' => ''],
                'choices' => $courses,
                'choice_value' => static fn (?Course $course) => $course?->getSlug(),
                'choice_label' => static fn (Course $course) => $course->getTitle(),
                // The script narrows the location select to the locations of the chosen course.
                'choice_attr' => static fn (Course $course) => [
                    'data-locations' => implode(',', array_map(
                        static fn (Location $location) => $location->getId(),
                        $course->getLocations(),
                    )),
                ],
            ])
            ->add('location', ChoiceType::class, [
                'label' => 'registration.location',
                'placeholder' => 'registration.choose_location',
                'attr' => ['data-location-select' => ''],
                'choices' => $this->courses->findLocations(),
                'choice_value' => static fn (?Location $location) => $location?->getId(),
                'choice_label' => static fn (Location $location) => $location->getCity(),
            ])
            ->add('firstname', TextType::class, [
                'label' => 'registration.firstname',
                'attr' => ['autocomplete' => 'given-name'],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'registration.lastname',
                'attr' => ['autocomplete' => 'family-name'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'registration.email',
                'attr' => ['autocomplete' => 'email'],
            ])
            ->add('phone', TelType::class, [
                'label' => 'registration.phone',
                'required' => false,
                'attr' => ['autocomplete' => 'tel'],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'registration.message',
                'required' => false,
                'attr' => ['rows' => 4],
            ])
            ->add('consent', CheckboxType::class, [
                'label' => 'registration.consent',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegistrationData::class,
            // The server validates and shows all errors at once, instead of the browser stopping at the first.
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
