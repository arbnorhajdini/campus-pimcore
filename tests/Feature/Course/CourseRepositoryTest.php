<?php

declare(strict_types=1);

namespace App\Tests\Feature\Course;

use App\Course\Category;
use App\Course\CourseFilter;
use App\Course\CourseRepository;
use App\Tests\Factory\CourseFactory;
use App\Tests\Factory\LocationFactory;

it('finds the courses of a category', function () {
    $design = CourseFactory::new()->inCategory(Category::Design)->create();
    CourseFactory::new()->inCategory(Category::It)->create();

    $courses = (new CourseRepository())->findByFilter(new CourseFilter(category: Category::Design));

    expect($courses)->toHaveCount(1)
        ->and($courses[0]->getId())->toBe($design->getId());
});

it('finds the courses at a location', function () {
    $bern = LocationFactory::createOne();
    $inBern = CourseFactory::new()->at($bern, LocationFactory::createOne())->create();
    CourseFactory::createOne();

    $courses = (new CourseRepository())->findByFilter(new CourseFilter(locationId: $bern->getId()));

    expect($courses)->toHaveCount(1)
        ->and($courses[0]->getId())->toBe($inBern->getId());
});

it('leaves out unpublished courses', function () {
    CourseFactory::new()->unpublished()->create();

    $courses = (new CourseRepository())->findByFilter(new CourseFilter());

    expect($courses)->toBeEmpty();
});

it('finds a course by its slug', function () {
    $course = CourseFactory::new()->with(['slug' => 'web-entwicklung'])->create();

    $found = (new CourseRepository())->findBySlug('web-entwicklung');

    expect($found?->getId())->toBe($course->getId());
});
