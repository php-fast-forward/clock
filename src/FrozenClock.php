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

use InvalidArgumentException;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Psr\Clock\ClockInterface;

/**
 * Provides the current system time in a predictable timezone.
 */
final readonly class FrozenClock implements ClockInterface
{
    private DateTimeImmutable $now;

    /**
     * Creates a new frozen clock.
     *
     * @param DateTimeInterface|ClockInterface|string|int|float $clock the time to freeze, or a ClockInterface to use its current time
     */
    public function __construct(DateTimeInterface|ClockInterface|string|int|float $clock = 'now')
    {
        if ($clock instanceof ClockInterface) {
            $clock = $clock->now();
        }

        if (\is_int($clock) || \is_float($clock)) {
            $clock = $this->fromTimestamp($clock);
        }

        $this->now = $clock instanceof DateTimeInterface
            ? DateTimeImmutable::createFromInterface($clock)
            : new DateTimeImmutable($clock);
    }

    /**
     * Returns the current date-time.
     */
    public function now(): DateTimeImmutable
    {
        return $this->now;
    }

    /**
     * @param float|int $timestamp
     *
     * @return DateTimeImmutable
     *
     * @throws InvalidArgumentException
     */
    private function fromTimestamp(float|int $timestamp): DateTimeImmutable
    {
        $dateTime = DateTimeImmutable::createFromFormat(
            'U.u',
            \sprintf('%.6F', $timestamp),
            new DateTimeZone('UTC'),
        );

        if (false === $dateTime) {
            throw new InvalidArgumentException('Invalid timestamp.');
        }

        return $dateTime->setTimezone(new DateTimeZone(date_default_timezone_get()));
    }
}
