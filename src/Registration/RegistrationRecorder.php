<?php

declare(strict_types=1);

namespace App\Registration;

use OpenDxp\Model\DataObject;
use OpenDxp\Model\DataObject\Registration;

/**
 * Stores a registration as a data object in /Anmeldungen, where the school sees it in the admin.
 */
final class RegistrationRecorder
{
    public const string FOLDER = '/Anmeldungen';

    public function record(RegistrationData $data, string $locale): Registration
    {
        $registration = new Registration();
        $registration->setParent(DataObject\Service::createFolderByPath(self::FOLDER));
        $registration->setKey(sprintf('%s-%s', date('Y-m-d-His'), bin2hex(random_bytes(3))));
        $registration->setCourse($data->course);
        $registration->setLocation($data->location);
        $registration->setFirstname($data->firstname);
        $registration->setLastname($data->lastname);
        $registration->setEmail($data->email);
        $registration->setPhone($data->phone);
        $registration->setMessage($data->message);
        $registration->setLocale($locale);
        $registration->setPublished(true);
        $registration->save();

        return $registration;
    }
}
