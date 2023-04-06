<?php

declare(strict_types=1);

namespace Instapro\CodingStandard\Test\Rule;

use Instapro\CodingStandard\Rule\ConstructorArgumentsOnNewLinesRule;
use Instapro\CodingStandard\Test\AbstractFixer;
use PhpCsFixer\Fixer\FixerInterface;

/**
 * @internal
 *
 * @small
 */
final class ConstructorArgumentsOnNewLinesRuleTest extends AbstractFixer
{
    protected function createFixer(): FixerInterface
    {
        return new ConstructorArgumentsOnNewLinesRule();
    }

    /**
     * @test
     * @dataProvider provideFixCases
     */
    public function it_formats_code_properly(string $expected, ?string $input = null): void
    {
        $this->doTest($expected, $input);
    }

    public static function provideFixCases(): iterable
    {
        yield 'empty class' => [
            '<?php class Foo {}',
        ];

        yield 'No constructor' => [
            '<?php
class Foo
{
    public function a(): void
    {
    }
}',
        ];

        yield 'No constructor arguments' => [
            '<?php
class Sample
{
    public function __construct()
    {
    }
}
',
        ];

        yield 'One constructor argument' => [
            '<?php
class Sample
{
    public function __construct(Argument $a)
    {
    }
}
',
        ];

        yield 'Multiple constructor arguments on different lines' => [
            '<?php
class Sample
{
    public function __construct(
    Argument $a,
Foo $b
    ) {
    }
}
',
            '<?php
class Sample
{
    public function __construct(
    Argument $a,
    Foo $b
    ) {
    }
}
',
        ];

        yield 'Multiple constructor arguments on same line' => [
            '<?php
class Sample
{
    public function __construct(
Argument $a,
Foo $b,

)
    {
    }
}
',
            '<?php
class Sample
{
    public function __construct(Argument $a, Foo $b,)
    {
    }
}
',
        ];
    }
}
