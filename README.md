# Instapro code standards

This project holds the PHP CS Fixer code standards we want to use company-wide.

## How to use
Install this package as a dev requirement with composer and change your `.php-cs-fixer.dist.php` file to
load the initial ruleset from `/vendor/instapro/code-standards/src/codeStandards.php`.

An example `.php-cs-fixer.dist.php`:

```php
<?php

declare(strict_types=1);

$codeStandards = include __DIR__ . '/vendor/instapro/code-standards/src/codeStandards.php';

return $codeStandards
    ->setFinder(
        PhpCsFixer\Finder::create()
            // Set your custom finder rules here
            ->in([
                __DIR__ . '/src',
                __DIR__ . '/tests',
            ])
            ->name('*.php'),
    );
```
