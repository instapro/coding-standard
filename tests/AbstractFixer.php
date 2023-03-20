<?php

declare(strict_types=1);

namespace Instapro\CodeStandards\Test;

use Exception;
use InvalidArgumentException;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Linter\CachingLinter;
use PhpCsFixer\Linter\ProcessLinter;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use function count;

abstract class AbstractFixer extends TestCase
{
    private FixerInterface $fixer;

    private CachingLinter $linter;

    protected function setUp(): void
    {
        $this->fixer = $this->createFixer();
        $this->linter = new CachingLinter(
            new ProcessLinter(),
        );
    }

    abstract protected function createFixer(): FixerInterface;

    protected function doTest(string $expected, ?string $input = null): void
    {
        if ($expected === $input) {
            throw new InvalidArgumentException('Input parameter must not be equal to expected parameter.');
        }

        $file = $this->getTestFile();
        $fileIsSupported = $this->fixer->supports($file);

        if ($input !== null) {
            self::assertNull($this->lintSource($input));

            Tokens::clearCache();
            $tokens = Tokens::fromCode($input);

            if ($fileIsSupported) {
                self::assertTrue($this->fixer->isCandidate($tokens), 'Fixer must be a candidate for input code.');
                self::assertFalse($tokens->isChanged(), 'Fixer must not touch Tokens on candidate check.');
                $this->fixer->fix($file, $tokens);
            }

            self::assertSame(
                $tokens->generateCode(),
                $expected,
                'Code build on input code must match expected code.',
            );
            self::assertTrue($tokens->isChanged(), 'Tokens collection built on input code must be marked as changed after fixing.');

            $tokens->clearEmptyTokens();

            self::assertSame(
                count($tokens),
                count(array_unique(array_map(static fn (Token $token): string => spl_object_hash($token), $tokens->toArray()))),
                'Token items inside Tokens collection must be unique.',
            );

            Tokens::clearCache();

            return;
        }

        self::assertNull($this->lintSource($expected));

        Tokens::clearCache();
        $tokens = Tokens::fromCode($expected);

        if ($fileIsSupported) {
            $this->fixer->fix($file, $tokens);
        }

        self::assertSame(
            $tokens->generateCode(),
            $expected,
            'Code build on expected code must not change.',
        );
    }

    protected function getTestFile(): SplFileInfo
    {
        return new SplFileInfo(__FILE__);
    }

    protected function lintSource(string $source): ?string
    {
        try {
            $this->linter->lintSource($source)->check();
        } catch (Exception $e) {
            return $e->getMessage() . "\n\nSource:\n{$source}";
        }

        return null;
    }
}
