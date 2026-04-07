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

use DateTimeImmutable;
use DateTimeZone;
use FastForward\Clock\SystemClock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(SystemClock::class)]
final class SystemClockTest extends TestCase
{
    /**
     * @return void
     */
    #[Test]
    public function nowWithoutTimezoneWillReturnTheCurrentTimeInTheDefaultTimezone(): void
    {
        $clock = new SystemClock();
        $now = $clock->now();

        self::assertInstanceOf(DateTimeImmutable::class, $now);
        self::assertSame(date_default_timezone_get(), $now->getTimezone()->getName());
    }

    /**
     * @param string $timezone
     *
     * @return void
     */
    #[Test]
    #[DataProvider('provideTimeZones')]
    public function nowWithStringTimezoneWillReturnTheCurrentTimeInTheConfiguredTimezone(string $timezone): void
    {
        $clock = new SystemClock($timezone);
        $now = $clock->now();

        self::assertInstanceOf(DateTimeImmutable::class, $now);
        self::assertSame($timezone, $now->getTimezone()->getName());
    }

    /**
     * @param string $timezone
     *
     * @return void
     */
    #[Test]
    #[DataProvider('provideTimeZones')]
    public function nowWithDateTimeZoneWillReturnTheCurrentTimeInTheConfiguredTimezone(string $timezone): void
    {
        $clock = new SystemClock(new DateTimeZone($timezone));
        $now = $clock->now();

        self::assertInstanceOf(DateTimeImmutable::class, $now);
        self::assertSame($timezone, $now->getTimezone()->getName());
    }

    /**
     * @return iterable
     */
    public static function provideTimeZones(): iterable
    {
        $identifiers = DateTimeZone::listIdentifiers();
        $timezones = array_rand(array_flip($identifiers), 10);

        return array_chunk($timezones, 1);
    }
}
