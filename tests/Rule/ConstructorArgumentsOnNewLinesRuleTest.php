<?php

declare(strict_types=1);

namespace Instapro\CodingStandard\Test\Rule;

use Instapro\CodingStandard\Rule\ConstructorArgumentsOnNewLinesRule;
use Instapro\CodingStandard\Test\AbstractFixer;
use PhpCsFixer\Fixer\FixerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;

/**
 * @internal
 */
#[Small]
final class ConstructorArgumentsOnNewLinesRuleTest extends AbstractFixer
{
    protected function createFixer(): FixerInterface
    {
        return new ConstructorArgumentsOnNewLinesRule();
    }

    #[Test]
    #[DataProvider('provideFixCases')]
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
