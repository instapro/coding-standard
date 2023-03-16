<?php

declare(strict_types=1);

namespace Instapro\CodeStandards\Rule;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;
use function in_array;
use function str_contains;
use const PHP_EOL;
use const T_FUNCTION;

final class ConstructorArgumentsOnNewLinesRule implements FixerInterface
{
    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(T_FUNCTION);
    }

    public function isRisky(): bool
    {
        return false;
    }

    public function fix(SplFileInfo $file, Tokens $tokens): void
    {
        foreach ($tokens->toArray() as $index => $token) {
            if (strtolower($token->getContent()) !== '__construct') {
                // Skip anything that isn't a __construct function
                continue;
            }

            $previousToken = $tokens->offsetGet($tokens->getNonWhitespaceSibling($index, -1));
            if (!$previousToken->isGivenKind(T_FUNCTION)) {
                // Skip if the previous token isn't a function token. We're dealing with a parent::__construct call
                continue;
            }

            $openingBraceIndex = $tokens->getNextMeaningfulToken($index);
            $closingBraceIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $openingBraceIndex);

            if ($closingBraceIndex - $openingBraceIndex === 1) {
                // Skip __construct methods with no arguments
                continue;
            }

            $hasSingleArgument = true;
            for ($i = $openingBraceIndex + 1; $i < $closingBraceIndex; ++$i) {
                if (
                    $tokens->offsetGet($i)->getContent() === ','
                    || in_array(
                        $tokens->offsetGet($i)->getName(),
                        [
                            'CT::T_CONSTRUCTOR_PROPERTY_PROMOTION_PUBLIC',
                            'CT::T_CONSTRUCTOR_PROPERTY_PROMOTION_PROTECTED',
                            'CT::T_CONSTRUCTOR_PROPERTY_PROMOTION_PRIVATE',
                        ],
                    )
                ) {
                    $hasSingleArgument = false;
                    break;
                }
            }

            if ($hasSingleArgument) {
                // Skip __construct methods with a single non-promoted argument
                continue;
            }

            $tokenSlice = [];
            $tokenSlice[] = $tokens->offsetGet($openingBraceIndex);

            if (!str_contains($tokens->offsetGet($openingBraceIndex + 1)->getContent(), PHP_EOL)) {
                $tokenSlice[] = new Token(PHP_EOL);
            }

            $skipWhitespaceTokenAfterComma = false;
            $outsideAttribute = true;
            for ($i = $openingBraceIndex + 1; $i < $closingBraceIndex; ++$i) {
                $token = $tokens->offsetGet($i);
                if ($token->getContent() === '#[') {
                    $outsideAttribute = false;
                }
                if ($skipWhitespaceTokenAfterComma === false) {
                    $tokenSlice[] = $token;
                }
                $skipWhitespaceTokenAfterComma = false;
                if ($token->getContent() === ',' && $outsideAttribute) {
                    $tokenSlice[] = new Token(PHP_EOL);
                    $skipWhitespaceTokenAfterComma = true;
                }
            }

            if (!str_contains($tokens->offsetGet($closingBraceIndex - 1)->getContent(), PHP_EOL)) {
                $tokenSlice[] = new Token(PHP_EOL);
            }

            $tokenSlice[] = $tokens->offsetGet($closingBraceIndex);
            $tokens->clearRange($openingBraceIndex, $closingBraceIndex);
            $tokens->insertAt($openingBraceIndex, $tokenSlice);
        }
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition('Make sure that each constructor argument is on its own line', []);
    }

    public function getName(): string
    {
        return 'Instapro/constructor_arguments_on_new_lines';
    }

    public function getPriority(): int
    {
        return 99999; // Make sure we run as the first fixer, so we don't need to fix any indentation issues
    }

    public function supports(SplFileInfo $file): bool
    {
        return true;
    }
}
