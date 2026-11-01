<?php

declare(strict_types=1);

use App\Controller\CourseController;
use App\Controller\DefaultController;
use OpenDxp\Model\Document\Editable;
use OpenDxp\Model\Document\Page;
use OpenDxp\Test\Document\Brick;
use OpenDxp\Test\Factory\DocumentPageFactory;

/**
 * The start page of a language, like /de. Its language property is inherited by every page below.
 */
function languageRoot(string $language, Brick ...$bricks): Page
{
    return DocumentPageFactory::new()
        ->with(['key' => $language])
        ->withController(DefaultController::class, 'homeAction')
        ->withInheritableProperty('language', 'text', $language)
        ->withBricks('content', ...$bricks)
        ->create();
}

function courseList(Page $languageRoot, string $key): Page
{
    return DocumentPageFactory::new()
        ->withParent($languageRoot)
        ->with(['key' => $key])
        ->withController(CourseController::class, 'listAction')
        ->create();
}

/**
 * An editable filled the same way the editor in the admin fills it.
 */
function editable(string $type, mixed $data): Editable
{
    $class = 'OpenDxp\\Model\\Document\\Editable\\' . ucfirst($type);
    $editable = new $class();
    $editable->setDataFromEditmode($data);

    return $editable;
}
