<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use OpenDxp\Model\DataObject\Location;
use OpenDxp\Test\Factory\AbstractDataObjectFactory;

/**
 * @extends AbstractDataObjectFactory<Location>
 */
final class LocationFactory extends AbstractDataObjectFactory
{
    public static function class(): string
    {
        return Location::class;
    }

    protected function defaults(): array
    {
        $city = self::faker()->unique()->city();

        return [
            ...parent::defaults(),
            'key' => self::faker()->unique()->slug(2),
            'name' => "Campus {$city}",
            'city' => $city,
            'published' => true,
        ];
    }
}
