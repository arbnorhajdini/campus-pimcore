<?php

declare(strict_types=1);

namespace App\Course;

use OpenDxp\Model\DataObject\Course;
use OpenDxp\Model\DataObject\Location;

/**
 * Loads published courses and locations. Listings only return published objects by default.
 */
final class CourseRepository
{
    /**
     * @return list<Course>
     */
    public function findByFilter(CourseFilter $filter): array
    {
        $listing = new Course\Listing();
        $conditions = [];
        $parameters = [];

        if ($filter->category !== null) {
            $conditions[] = 'category = :category';
            $parameters['category'] = $filter->category->value;
        }

        if ($filter->locationId !== null) {
            // A many-to-many relation is stored as ",3,5," in the query table.
            $conditions[] = 'locations LIKE :location';
            $parameters['location'] = "%,{$filter->locationId},%";
        }

        if ($conditions !== []) {
            $listing->setCondition(implode(' AND ', $conditions), $parameters);
        }
        $listing->setOrderKey('startDate');

        return array_values($listing->load());
    }

    /**
     * @return list<Course>
     */
    public function findFeatured(int $limit): array
    {
        $listing = new Course\Listing();
        $listing->setCondition('featured = 1');
        $listing->setOrderKey('startDate');
        $listing->setLimit($limit);

        return array_values($listing->load());
    }

    public function findBySlug(string $slug): ?Course
    {
        $listing = new Course\Listing();
        $listing->setCondition('slug = :slug', ['slug' => $slug]);
        $listing->setLimit(1);

        $course = $listing->current();

        return $course instanceof Course ? $course : null;
    }

    /**
     * @return list<Location>
     */
    public function findLocations(): array
    {
        $listing = new Location\Listing();
        $listing->setOrderKey('city');

        return array_values($listing->load());
    }
}
