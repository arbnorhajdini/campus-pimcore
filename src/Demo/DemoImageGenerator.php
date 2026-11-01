<?php

declare(strict_types=1);

namespace App\Demo;

use Imagick;
use ImagickDraw;
use ImagickPixel;

/**
 * Creates abstract placeholder images for the demo courses, so the repository ships no photos.
 */
final class DemoImageGenerator
{
    private const int WIDTH = 1200;

    private const int HEIGHT = 800;

    /** Gradient colors per course category. */
    private const array COLORS = [
        'it' => ['#1d3f72', '#3f8fd2'],
        'engineering' => ['#6e3214', '#e0873a'],
        'business' => ['#174f40', '#53ab8f'],
        'design' => ['#5c2560', '#cf6aa4'],
    ];

    /**
     * Returns a JPEG. The same seed always gives the same image.
     */
    public function generate(string $category, string $seed): string
    {
        [$from, $to] = self::COLORS[$category] ?? self::COLORS['it'];

        $image = new Imagick();
        $image->setOption('gradient:angle', '135');
        $image->newPseudoImage(self::WIDTH, self::HEIGHT, "gradient:{$from}-{$to}");

        mt_srand(crc32($seed));
        $draw = new ImagickDraw();
        $draw->setFillColor(new ImagickPixel('rgba(255, 255, 255, 0.12)'));
        for ($i = 0; $i < 5; $i++) {
            $x = mt_rand(0, self::WIDTH);
            $y = mt_rand(0, self::HEIGHT);
            $radius = mt_rand(80, 320);
            $draw->circle($x, $y, $x + $radius, $y);
        }
        $image->drawImage($draw);

        $image->setImageFormat('jpeg');
        $image->setImageCompressionQuality(85);

        return $image->getImageBlob();
    }
}
