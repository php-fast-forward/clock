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

namespace FastForward\Clock\Tests;

use DateTimeZone;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use FastForward\Clock\FrozenClock;
use FastForward\Clock\ServiceProvider\Factory\DateTimeZoneFactory;
use FastForward\Clock\SystemClock;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface as PsrClockInterface;

#[CoversClass(FrozenClock::class)]
#[UsesClass(SystemClock::class)]
#[UsesClass(DateTimeZoneFactory::class)]
final class FrozenClockTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function nowWithoutArgumentsWillReturnCurrentTime(): void
    {
        $clock = new FrozenClock();
        $now = $clock->now();

        self::assertInstanceOf(DateTimeImmutable::class, $now);
    }

    /**
     * @return void
     */
    #[Test]
    public function nowWillAlwaysReturnTheSameFrozenTime(): void
    {
        $clock = new FrozenClock('2026-04-07 10:00:00 UTC');

        $now1 = $clock->now();
        $now2 = $clock->now();

        self::assertSame($now1, $now2);
        self::assertSame('2026-04-07 10:00:00', $now1->format('Y-m-d H:i:s'));
    }

    /**
     * @return void
     */
    #[Test]
    public function nowWithStringDateWillReturnTheConfiguredTime(): void
    {
        $clock = new FrozenClock('2025-01-15 14:30:00');
        $now = $clock->now();

        self::assertInstanceOf(DateTimeImmutable::class, $now);
        self::assertSame('2025-01-15 14:30:00', $now->format('Y-m-d H:i:s'));
    }

    /**
     * @return void
     */
    #[Test]
    public function nowWithDateTimeImmutableWillReturnTheConfiguredTime(): void
    {
        $dateTime = new DateTimeImmutable('2025-06-20 08:15:30', new DateTimeZone('America/Sao_Paulo'));
        $clock = new FrozenClock($dateTime);
        $now = $clock->now();

        self::assertInstanceOf(DateTimeImmutable::class, $now);
        self::assertSame('2025-06-20 08:15:30', $now->format('Y-m-d H:i:s'));
    }

    /**
     * @return void
     */
    #[Test]
    public function nowWithDateTimeMutableWillConvertToImmutable(): void
    {
        $dateTime = new DateTime('2025-12-25 00:00:00', new DateTimeZone('Europe/Lisbon'));
        $clock = new FrozenClock($dateTime);
        $now = $clock->now();

        self::assertInstanceOf(DateTimeImmutable::class, $now);
        self::assertSame('2025-12-25 00:00:00', $now->format('Y-m-d H:i:s'));
    }

    /**
     * @return void
     */
    #[Test]
    public function nowWithIntegerTimestampWillReturnTheConfiguredTime(): void
    {
        $timestamp = 1777776000;
        $clock = new FrozenClock($timestamp);
        $now = $clock->now();

        self::assertInstanceOf(DateTimeImmutable::class, $now);
        self::assertSame($timestamp, $now->getTimestamp());
    }

    /**
     * @return void
     */
    #[Test]
    public function nowWithFloatTimestampWillReturnTheConfiguredTime(): void
    {
        $timestamp = 1777776000.123456;
        $clock = new FrozenClock($timestamp);
        $now = $clock->now();

        self::assertInstanceOf(DateTimeImmutable::class, $now);
        self::assertEqualsWithDelta(1777776000, $now->getTimestamp(), 1);
    }

    /**
     * @return void
     */
    #[Test]
    public function nowWithInvalidTimestampWillThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new FrozenClock(\PHP_INT_MAX * 2);
    }

    /**
     * @return void
     */
    #[Test]
    public function frozenClockImplementsPsrClockInterface(): void
    {
        $clock = new FrozenClock();

        self::assertInstanceOf(PsrClockInterface::class, $clock);
    }

    /**
     * @return void
     */
    #[Test]
    public function nowReturnsDateTimeImmutableInterface(): void
    {
        $clock = new FrozenClock('2026-01-01 00:00:00');

        self::assertInstanceOf(DateTimeInterface::class, $clock->now());
    }

    /**
     * @return void
     */
    #[Test]
    public function nowWithClockInterfaceWillFreezeTheTimeFromThatClock(): void
    {
        $systemClock = new SystemClock('America/New_York');
        $frozenClock = new FrozenClock($systemClock);

        self::assertSame($systemClock->now()->getTimestamp(), $frozenClock->now()->getTimestamp());
    }
}
