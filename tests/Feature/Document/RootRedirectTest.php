<?php

declare(strict_types=1);

namespace App\Tests\Feature\Document;

use App\Controller\DefaultController;
use OpenDxp\Model\Document\Page;
use OpenDxp\TestFoundation\Browser;

it('sends the visitor to the language they prefer', function (string $acceptLanguage, string $target) {
    $root = Page::getById(1);
    $root?->setController(DefaultController::class . '::rootAction')->save();

    Browser::start()
        ->interceptRedirects()
        ->get('/', ['headers' => ['Accept-Language' => $acceptLanguage]])
        ->assertRedirectedTo($target);
})->with([
    'French' => ['fr-CH,fr;q=0.9', '/fr'],
    'German' => ['de-CH,de;q=0.9', '/de'],
    'a language the site does not offer' => ['en-US,en;q=0.9', '/de'],
]);
