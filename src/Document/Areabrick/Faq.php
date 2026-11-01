<?php

declare(strict_types=1);

namespace App\Document\Areabrick;

use OpenDxp\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use OpenDxp\Extension\Document\Areabrick\Attribute\AsAreabrick;

/**
 * Questions and answers. Each entry is a <details> element, so it opens and closes without JavaScript.
 */
#[AsAreabrick(id: 'faq')]
final class Faq extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'Häufige Fragen';
    }

    public function getDescription(): string
    {
        return 'Fragen und Antworten zum Aufklappen. Beliebig viele Einträge.';
    }

    public function getIcon(): string
    {
        return '/bundles/opendxpadmin/img/flat-color-icons/faq.svg';
    }
}
