<?php

declare(strict_types=1);

namespace App\Tests\Feature\Registration;

use App\Tests\Factory\CourseFactory;
use App\Tests\Factory\LocationFactory;
use OpenDxp\Model\DataObject\Registration;
use OpenDxp\TestFoundation\Browser;

it('stores a valid registration', function () {
    registrationPage(languageRoot('de'), 'anmeldung');
    $location = LocationFactory::new()->with(['city' => 'Luzern'])->create();
    $course = CourseFactory::new()->at($location)->with(['slug' => 'ux-design'])->create();

    Browser::visit('/de/anmeldung?course=ux-design')
        ->selectFieldOption('Standort', 'Luzern')
        ->fillField('Vorname', 'Anna')
        ->fillField('Nachname', 'Muster')
        ->fillField('E-Mail', 'anna@example.ch')
        ->checkField('registration[consent]')
        ->click('Anmeldung senden')
        ->assertSuccessful()
        ->assertSee('Vielen Dank für deine Anmeldung');

    $registrations = (new Registration\Listing())->load();
    expect($registrations)->toHaveCount(1)
        ->and($registrations[0]->getCourse()?->getId())->toBe($course->getId())
        ->and($registrations[0]->getLocation()?->getId())->toBe($location->getId())
        ->and($registrations[0]->getEmail())->toBe('anna@example.ch')
        ->and($registrations[0]->getLocale())->toBe('de');
});

it('lists every missing field in the error summary', function () {
    registrationPage(languageRoot('de'), 'anmeldung');
    CourseFactory::createOne();

    Browser::visit('/de/anmeldung')
        ->click('Anmeldung senden')
        ->assertStatus(422)
        ->assertElementCount('.notice--error li', 6)
        ->assertSee('Bitte gib deinen Vornamen ein.');
});

it('rejects a location where the course does not take place', function () {
    registrationPage(languageRoot('de'), 'anmeldung');
    LocationFactory::new()->with(['city' => 'Bern'])->create();
    CourseFactory::new()->at(LocationFactory::createOne())->with(['slug' => 'ux-design'])->create();

    Browser::visit('/de/anmeldung?course=ux-design')
        ->selectFieldOption('Standort', 'Bern')
        ->fillField('Vorname', 'Anna')
        ->fillField('Nachname', 'Muster')
        ->fillField('E-Mail', 'anna@example.ch')
        ->checkField('registration[consent]')
        ->click('Anmeldung senden')
        ->assertStatus(422)
        ->assertSee('Dieser Kurs findet an diesem Standort nicht statt.');
});
