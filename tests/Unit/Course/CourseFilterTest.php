<?php

declare(strict_types=1);

namespace App\Tests\Unit\Course;

use App\Course\Category;
use App\Course\CourseFilter;
use Symfony\Component\HttpFoundation\Request;

it('reads category and location from the query string', function () {
    $request = Request::create('/de/kurse', parameters: ['category' => 'design', 'location' => '7']);

    $filter = CourseFilter::fromRequest($request);

    expect($filter->category)->toBe(Category::Design)
        ->and($filter->locationId)->toBe(7)
        ->and($filter->isActive())->toBeTrue();
});

it('ignores values it does not know', function (array $query) {
    $request = Request::create('/de/kurse', parameters: $query);

    $filter = CourseFilter::fromRequest($request);

    expect($filter->isActive())->toBeFalse();
})->with([
    'no filter' => [[]],
    'an unknown category' => [['category' => 'cooking']],
    'a location that is not a number' => [['location' => 'luzern']],
    'a negative location' => [['location' => '-3']],
]);
