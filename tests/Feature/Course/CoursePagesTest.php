<?php

declare(strict_types=1);

namespace App\Tests\Feature\Course;

use App\Course\Category;
use App\Tests\Factory\CourseFactory;
use OpenDxp\TestFoundation\Browser;

it('lists only the courses that match the filter', function () {
    courseList(languageRoot('de'), 'kurse');
    CourseFactory::new()->inCategory(Category::Design)->titled('UX/UI Design', 'Design UX/UI')->create();
    CourseFactory::new()->inCategory(Category::It)->titled('Informatik HF', 'Informatique ES')->create();

    Browser::visit('/de/kurse?category=design')
        ->assertSuccessful()
        ->assertSee('UX/UI Design')
        ->assertNotSee('Informatik HF');
});

it('shows a course in the language of the URL', function (string $url, string $title) {
    languageRoot('de');
    languageRoot('fr');
    CourseFactory::new()->with(['slug' => 'ux-design'])->titled('UX/UI Design', 'Design UX/UI')->create();

    Browser::visit($url)
        ->assertSuccessful()
        ->assertSeeIn('h1', $title);
})->with([
    'in German' => ['/de/kurse/ux-design', 'UX/UI Design'],
    'in French' => ['/fr/cours/ux-design', 'Design UX/UI'],
]);

it('links a course to the same course in the other language', function () {
    languageRoot('de');
    languageRoot('fr');
    CourseFactory::new()->with(['slug' => 'ux-design'])->create();

    Browser::visit('/de/kurse/ux-design')
        ->assertElementAttributeContains('link[hreflang="fr"]', 'href', '/fr/cours/ux-design')
        ->assertElementAttributeContains('.language-switch__link[hreflang="fr"]', 'href', '/fr/cours/ux-design');
});

it('answers 404 for a course that does not exist', function () {
    languageRoot('de');

    Browser::visit('/de/kurse/gibt-es-nicht')
        ->assertStatus(404);
});
