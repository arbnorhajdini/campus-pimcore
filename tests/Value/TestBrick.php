<?php

declare(strict_types=1);

namespace App\Tests\Value;

use OpenDxp\Model\Document\Editable;
use OpenDxp\Test\Document\Brick;

/**
 * A brick of an areablock with its editables, named relative to the brick: "headline", "items:1.question".
 */
final readonly class TestBrick implements Brick
{
    /**
     * @param array<string, Editable> $editables
     */
    public function __construct(
        private string $id,
        private array $editables = [],
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function editables(): array
    {
        return $this->editables;
    }
}
