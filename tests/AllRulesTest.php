<?php

declare(strict_types=1);

namespace Instapro\CodingStandard\Test;

use PhpCsFixer\Console\Application;
use PhpCsFixer\Console\Command\FixCommandExitStatusCalculator;
use PHPUnit\Framework\Attributes\Large;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use const JSON_THROW_ON_ERROR;

/**
 * @internal
 */
#[Large]
final class AllRulesTest extends TestCase
{
    public const FILE = __DIR__ . '/Files/AReallyBadFile.php';

    private Application $application;

    protected function setUp(): void
    {
        $this->application = new Application();
    }

    #[Test]
    public function it_formats_code_properly(): void
    {
        $output = $this->executeFixCommand();
        $json = json_decode($output, true, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('files', $json);
        self::assertCount(1, $json['files']);

        self::assertArrayHasKey('diff', $json['files'][0]);
        $diff = $json['files'][0]['diff'];
        $diff = $this->removeFilePath($diff);

        self::assertSame(file_get_contents(__DIR__ . '/Files/expected.diff'), $diff);
    }

    private function removeFilePath(string $diff): string
    {
        return str_replace(self::FILE, '__FILE__', $diff);
    }

    private function executeFixCommand(): string
    {
        $command = $this->application->find('fix');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'path' => [self::FILE],
            '--config' => __DIR__ . '/../.php-cs-fixer.dist.php',
            '--diff' => true,
            '--dry-run' => true,
            '--using-cache' => 'no',
            '--format' => 'json',
        ]);

        self::assertSame(FixCommandExitStatusCalculator::EXIT_STATUS_FLAG_HAS_CHANGED_FILES, $commandTester->getStatusCode());

        return $commandTester->getDisplay();
    }
}
