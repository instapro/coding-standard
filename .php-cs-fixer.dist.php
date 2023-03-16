<?php

declare(strict_types=1);

$codeStandards = include __DIR__ . '/src/codeStandards.php';

return $codeStandards
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in([
                __DIR__ . '/src',
                __DIR__ . '/tests',
            ])
            ->name('*.php'),
    );
