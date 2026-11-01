<?php

declare(strict_types=1);

use OpenDxp\Test\Expectation\Fields;
use OpenDxp\TestFoundation\TestCase;

require_once __DIR__ . '/Helpers/pages.php';

Fields::register();

pest()->extend(TestCase::class)->in('Feature');
