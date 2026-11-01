<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Course\Category;
use Carbon\Carbon;
use OpenDxp\Model\DataObject\Course;
use OpenDxp\Model\DataObject\Location;
use OpenDxp\Test\Factory\AbstractDataObjectFactory;

/**
 * @extends AbstractDataObjectFactory<Course>
 */
final class CourseFactory extends AbstractDataObjectFactory
{
    public static function class(): string
    {
        return Course::class;
    }

    public function inCategory(Category $category): static
    {
        return $this->with(['category' => $category->value]);
    }

    public function at(Location ...$locations): static
    {
        return $this->with(['locations' => $locations]);
    }

    public function featured(): static
    {
        return $this->with(['featured' => true]);
    }

    public function titled(string $german, string $french): static
    {
        return $this->withLocalizedValues('title', ['de' => $german, 'fr' => $french]);
    }

    protected function defaults(): array
    {
        $slug = self::faker()->unique()->slug(2);

        return [
            ...parent::defaults(),
            'key' => $slug,
            'slug' => $slug,
            'category' => Category::It->value,
            'format' => 'part-time',
            'durationSemesters' => 2,
            'startDate' => Carbon::now()->addMonth(),
            'featured' => false,
            'locations' => LocationFactory::new()->many(1),
            'published' => true,
        ];
    }

    protected function initialize(): static
    {
        // Title and teaser are mandatory in both languages, so a test gets them unless it sets its own.
        return parent::initialize()
            ->withLocalizedValues('title', ['de' => self::faker()->sentence(3), 'fr' => self::faker()->sentence(3)])
            ->withLocalizedValues('teaser', ['de' => self::faker()->sentence(), 'fr' => self::faker()->sentence()]);
    }
}
