<?php

declare(strict_types=1);

namespace App\Document\Areabrick;

use OpenDxp\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use OpenDxp\Extension\Document\Areabrick\Attribute\AsAreabrick;

#[AsAreabrick(id: 'text-image')]
final class TextImage extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'Text mit Bild';
    }

    public function getDescription(): string
    {
        return 'Überschrift, Text und ein Bild links oder rechts daneben.';
    }

    public function getIcon(): string
    {
        return '/bundles/opendxpadmin/img/flat-color-icons/picture.svg';
    }
}
