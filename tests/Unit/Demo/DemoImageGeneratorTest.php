<?php

declare(strict_types=1);

namespace App\Tests\Unit\Demo;

use App\Demo\DemoImageGenerator;

it('creates a JPEG in landscape format', function () {
    $jpeg = (new DemoImageGenerator())->generate('it', 'web-entwicklung');

    $size = getimagesizefromstring($jpeg);

    expect($size)->toBeArray()
        ->and($size['mime'] ?? null)->toBe('image/jpeg')
        ->and($size[0] ?? null)->toBe(1200)
        ->and($size[1] ?? null)->toBe(800);
});

it('creates the same image for the same seed only', function () {
    $generator = new DemoImageGenerator();
    $image = $generator->generate('design', 'ux-design');

    expect($generator->generate('design', 'ux-design'))->toBe($image)
        ->and($generator->generate('design', 'another-course') === $image)->toBeFalse();
});
