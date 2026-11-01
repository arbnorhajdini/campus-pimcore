<?php

declare(strict_types=1);

namespace App\Course;

use Symfony\Component\HttpFoundation\Request;

/**
 * The filter of the course list, read from the query string. Unknown values are ignored.
 */
final readonly class CourseFilter
{
    public function __construct(
        public ?Category $category = null,
        public ?int $locationId = null,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        // A location that is no positive number counts as no filter instead of a 400 error.
        $location = $request->query->filter('location', null, FILTER_VALIDATE_INT, [
            'flags' => FILTER_NULL_ON_FAILURE,
            'options' => ['min_range' => 1],
        ]);

        return new self(
            Category::tryFrom($request->query->getString('category')),
            is_int($location) ? $location : null,
        );
    }

    public function isActive(): bool
    {
        return $this->category !== null || $this->locationId !== null;
    }
}
