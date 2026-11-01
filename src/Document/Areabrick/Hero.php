<?php

declare(strict_types=1);

namespace App\Document\Areabrick;

use OpenDxp\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use OpenDxp\Extension\Document\Areabrick\Attribute\AsAreabrick;

/**
 * The large intro at the top of a page, with the h1 of the page and a call to action.
 */
#[AsAreabrick(id: 'hero')]
final class Hero extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'Hero';
    }

    public function getDescription(): string
    {
        return 'Einstieg oben auf der Seite: Titel, Einleitung und Button. Einmal pro Seite.';
    }

    public function getIcon(): string
    {
        return '/bundles/opendxpadmin/img/flat-color-icons/landscape.svg';
    }
}
