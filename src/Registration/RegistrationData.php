<?php

declare(strict_types=1);

namespace App\Registration;

use OpenDxp\Model\DataObject\Course;
use OpenDxp\Model\DataObject\Location;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * What a visitor enters in the registration form. The messages are translation keys of the domain validators.
 */
final class RegistrationData
{
    #[Assert\NotNull(message: 'registration.course.required')]
    public ?Course $course = null;

    #[Assert\NotNull(message: 'registration.location.required')]
    public ?Location $location = null;

    #[Assert\Length(max: 100)]
    #[Assert\NotBlank(message: 'registration.firstname.required')]
    public ?string $firstname = null;

    #[Assert\Length(max: 100)]
    #[Assert\NotBlank(message: 'registration.lastname.required')]
    public ?string $lastname = null;

    #[Assert\Email(message: 'registration.email.invalid')]
    #[Assert\NotBlank(message: 'registration.email.required')]
    public ?string $email = null;

    #[Assert\Length(max: 30)]
    #[Assert\Regex(pattern: '/^[0-9 +()\/-]*$/', message: 'registration.phone.invalid')]
    public ?string $phone = null;

    #[Assert\Length(max: 2000)]
    public ?string $message = null;

    #[Assert\IsTrue(message: 'registration.consent.required')]
    public bool $consent = false;

    /**
     * The select offers every location, so the server checks that the course actually takes place there.
     */
    #[Assert\Callback]
    public function validateLocation(ExecutionContextInterface $context): void
    {
        if ($this->course === null || $this->location === null) {
            return;
        }

        foreach ($this->course->getLocations() as $location) {
            if ($location->getId() === $this->location->getId()) {
                return;
            }
        }

        $context->buildViolation('registration.location.not_offered')
            ->atPath('location')
            ->addViolation();
    }
}
