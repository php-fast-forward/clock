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

namespace FastForward\Clock;

use DateTimeImmutable;
use DateTimeZone;
use Psr\Clock\ClockInterface;

/**
 * Provides the current system time in a predictable timezone.
 */
final readonly class SystemClock implements ClockInterface
{
    private ?DateTimeZone $timezone;

    /**
     * Creates a new system clock.
     *
     * @param DateTimeZone|string|null $timezone the timezone that SHOULD be used by this clock
     */
    public function __construct(DateTimeZone|string|null $timezone = null)
    {
        $this->timezone = \is_string($timezone) ? new DateTimeZone($timezone) : $timezone;
    }

    /**
     * Returns the current date-time.
     */
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->timezone);
    }
}
