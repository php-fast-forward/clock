<?php

declare(strict_types=1);

namespace FastForward\Clock\Tests;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

#[CoversNothing]
final class ConsumerSkillTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function bundledProgramsWillVerifyConsumerIntegration(): void
    {
        $root = \dirname(__DIR__);

        foreach (['application', 'container'] as $example) {
            $process = new Process([
                \PHP_BINARY,
                $root . '/resources/skills/fast-forward-clock/examples/' . $example . '.php',
                $root . '/vendor/autoload.php',
            ], $root, timeout: 10);
            $process->run();

            self::assertSame(0, $process->getExitCode(), $process->getErrorOutput() . $process->getOutput());
            self::assertSame($example . ": ok\n", $process->getOutput());
        }
    }
}
