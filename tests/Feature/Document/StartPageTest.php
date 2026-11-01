<?php

declare(strict_types=1);

namespace App\Tests\Feature\Document;

use App\Tests\Factory\CourseFactory;
use App\Tests\Value\TestBrick;
use OpenDxp\Test\Factory\DocumentPageFactory;
use OpenDxp\TestFoundation\Browser;

it('renders the hero title as the heading of the page', function () {
    languageRoot('de', new TestBrick('hero', [
        'title' => editable('input', 'Weiterbildung, die passt'),
    ]));

    Browser::visit('/de')
        ->assertSuccessful()
        ->assertSeeIn('h1', 'Weiterbildung, die passt');
});

it('shows only the featured courses', function () {
    languageRoot('de', new TestBrick('featured-courses'));
    CourseFactory::new()->featured()->titled('Informatik HF', 'Informatique ES')->create();
    CourseFactory::new()->titled('Projektmanagement', 'Gestion de projet')->create();

    Browser::visit('/de')
        ->assertSee('Informatik HF')
        ->assertNotSee('Projektmanagement');
});

it('leaves out the course section when no course is featured', function () {
    languageRoot('de', new TestBrick('featured-courses', [
        'headline' => editable('input', 'Beliebte Weiterbildungen'),
    ]));
    CourseFactory::createOne();

    Browser::visit('/de')
        ->assertSuccessful()
        ->assertNotSee('Beliebte Weiterbildungen');
});

it('renders the hero only once', function () {
    languageRoot(
        'de',
        new TestBrick('hero', ['title' => editable('input', 'Erster Hero')]),
        new TestBrick('hero', ['title' => editable('input', 'Zweiter Hero')]),
    );

    Browser::visit('/de')
        ->assertElementCount('h1', 1)
        ->assertNotSee('Zweiter Hero');
});

it('renders every entry of the FAQ as a details element', function () {
    languageRoot('de', new TestBrick('faq', [
        'items' => editable('block', [1, 2]),
        'items:1.question' => editable('input', 'Wann beginnt der Kurs?'),
        'items:2.question' => editable('input', 'Was kostet der Kurs?'),
    ]));

    Browser::visit('/de')
        ->assertElementCount('details.faq__item', 2)
        ->assertSeeIn('details.faq__item summary', 'Wann beginnt der Kurs?');
});

it('renders a page that an editor creates without choosing a controller', function () {
    DocumentPageFactory::new()
        ->withParent(languageRoot('de'))
        ->with(['key' => 'ueber-uns'])
        ->withBricks('content', new TestBrick('hero', ['title' => editable('input', 'Über uns')]))
        ->create();

    Browser::visit('/de/ueber-uns')
        ->assertSuccessful()
        ->assertSeeIn('h1', 'Über uns');
});
