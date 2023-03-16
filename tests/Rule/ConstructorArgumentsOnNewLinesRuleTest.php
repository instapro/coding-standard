<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @small
 */
final class ConstructorArgumentsOnNewLinesRuleTest extends TestCase
{
    /** @test */
    public function it_formats_code_properly(): void
    {
        // Instead of testing this one rule I suggest we write an example class with all mistakes that should be fixed
        // by the main configuration. Run the fixer on that file and see if it matches the expected output.
    }
}
