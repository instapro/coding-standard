<?php

declare(strict_types=1);

use Instapro\CodingStandard\Load;

return Load::configuration(
    PhpCsFixer\Finder::create()
        ->exclude('Files')
        ->in([
            __DIR__ . '/src',
            __DIR__ . '/tests',
        ])
        ->name('*.php'),
);
