<?php

declare(strict_types=1);

/**
 * This file is part of php-fast-forward/clock.
 *
 * This source file is subject to the license bundled
 * with this source code in the file LICENSE.
 *
 * @copyright Copyright (c) 2025-2026 Felipe Sayão Lobato Abreu <github@mentordosnerds.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 *
 * @see       https://github.com/php-fast-forward/clock
 * @see       https://github.com/php-fast-forward
 * @see       https://datatracker.ietf.org/doc/html/rfc2119
 */

namespace FastForward\Clock\Tests\ServiceProvider;

use DateTimeZone;
use FastForward\Clock\FrozenClock;
use FastForward\Clock\ServiceProvider\ClockServiceProvider;
use FastForward\Clock\ServiceProvider\Factory\DateTimeZoneFactory;
use FastForward\Clock\SystemClock;
use FastForward\Container\Factory\AliasFactory;
use FastForward\Container\Factory\InvokableFactory;
use FastForward\Container\ServiceProvider\ArrayServiceProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Clock\ClockInterface as PsrClockInterface;
use Psr\Container\ContainerInterface;

use function FastForward\Container\container;

#[CoversClass(ClockServiceProvider::class)]
#[UsesClass(DateTimeZoneFactory::class)]
#[UsesClass(FrozenClock::class)]
#[UsesClass(SystemClock::class)]
final class ClockServiceProviderTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @return void
     */
    #[Test]
    public function getFactoriesWillRegisterTheSystemClockAndItsAliases(): void
    {
        $factories = (new ClockServiceProvider())->getFactories();

        self::assertSame(
            [PsrClockInterface::class, SystemClock::class, DateTimeZone::class],
            array_keys($factories),
        );
        self::assertInstanceOf(DateTimeZoneFactory::class, $factories[DateTimeZone::class]);
        self::assertInstanceOf(AliasFactory::class, $factories[PsrClockInterface::class]);
        self::assertInstanceOf(InvokableFactory::class, $factories[SystemClock::class]);
    }

    /**
     * @return void
     */
    #[Test]
    public function getExtensionsWillReturnAnEmptyArray(): void
    {
        self::assertSame([], (new ClockServiceProvider())->getExtensions());
    }

    /**
     * @return void
     */
    #[Test]
    public function systemClockFactoryWillCreateASystemClockInstance(): void
    {
        $container = container(new ClockServiceProvider());
        $factory = $container->get(SystemClock::class);

        self::assertInstanceOf(SystemClock::class, $factory);
        self::assertInstanceOf(PsrClockInterface::class, $factory);
    }

    /**
     * @return void
     */
    #[Test]
    public function psrClockAliasWillResolveTheSystemClockFromTheContainer(): void
    {
        $container = $this->prophesize(ContainerInterface::class);
        $clock = new SystemClock();

        $container
            ->get(SystemClock::class)
            ->shouldBeCalledOnce()
            ->willReturn($clock);

        $factory = (new ClockServiceProvider())->getFactories()[PsrClockInterface::class];

        self::assertSame($clock, $factory($container->reveal()));
    }

    /**
     * @return void
     */
    #[Test]
    public function fastForwardContainerWillResolveAllAliasesToTheSameSystemClockInstance(): void
    {
        $container = container(new ClockServiceProvider());

        $systemClock = $container->get(SystemClock::class);
        $psrClock = $container->get(PsrClockInterface::class);

        self::assertInstanceOf(SystemClock::class, $systemClock);
        self::assertSame($systemClock, $psrClock);
    }

    /**
     * @return void
     */
    #[Test]
    public function fastForwardContainerWillAllowClockOverridesBeforeThePackageProvider(): void
    {
        $mockClock = new FrozenClock('2026-04-07 10:00:00 UTC');

        $overrideProvider = new ArrayServiceProvider([
            FrozenClock::class => static fn(): FrozenClock => $mockClock,
            SystemClock::class => static fn(ContainerInterface $container): FrozenClock => $container->get(
                FrozenClock::class,
            ),
        ]);

        $container = container($overrideProvider, new ClockServiceProvider());

        self::assertSame($mockClock, $container->get(PsrClockInterface::class));
        self::assertSame($mockClock, $container->get(FrozenClock::class));
        self::assertInstanceOf(FrozenClock::class, $container->get(SystemClock::class));
    }
}
