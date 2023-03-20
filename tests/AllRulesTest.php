<?php

declare(strict_types=1);

namespace Instapro\CodeStandards\Test;

use PhpCsFixer\Console\Application;
use PhpCsFixer\Console\Command\FixCommandExitStatusCalculator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use const JSON_THROW_ON_ERROR;

/**
 * @internal
 *
 * @large
 */
final class AllRulesTest extends TestCase
{
    public const FILE = __DIR__ . '/Files/AReallyBadFile.php';

    private Application $application;

    protected function setUp(): void
    {
        $this->application = new Application();
    }

    /** @test */
    public function it_formats_code_properly(): void
    {
        $output = $this->executeFixCommand();
        $json = json_decode($output, true, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('files', $json);
        self::assertCount(1, $json['files']);

        $diff = $json['files'][0]['diff'];
        $diff = $this->removeFilePath(self::FILE, $diff);

        self::assertSame(file_get_contents(__DIR__ . '/Files/expected.diff'), $diff);
    }

    private function removeFilePath(string $filePath, string $diff): string
    {
        return str_replace($filePath, '__FILE__', $diff);
    }

    private function executeFixCommand(): string
    {
        $command = $this->application->find('fix');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'path' => [self::FILE],
            '--config' => __DIR__ . '/../.php-cs-fixer.dist.php',
            '--diff' => 'true',
            '--dry-run' => true,
            '--using-cache' => 'no',
            '--format' => 'json',
        ]);

        self::assertSame(FixCommandExitStatusCalculator::EXIT_STATUS_FLAG_HAS_CHANGED_FILES, $commandTester->getStatusCode());

        return $commandTester->getDisplay();
    }
}
