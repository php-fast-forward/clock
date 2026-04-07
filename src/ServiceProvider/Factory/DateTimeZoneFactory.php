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

namespace FastForward\Clock\ServiceProvider\Factory;

use DateTimeZone;
use FastForward\Config\ConfigInterface;
use Psr\Container\ContainerInterface;

/**
 * Factory for creating DateTimeZone instances for the Fast Forward Container.
 *
 * This factory SHALL return a DateTimeZone instance based on configuration, or fallback to the default timezone.
 *
 * @see https://github.com/php-fast-forward/clock
 * @see https://github.com/php-fast-forward
 * @see https://datatracker.ietf.org/doc/html/rfc2119
 */
final class DateTimeZoneFactory
{
    /**
     * Creates a DateTimeZone instance using configuration from the container.
     *
     * If the container does not provide a ConfigInterface, or the configuration does not specify a timezone,
     * this method SHALL return a DateTimeZone using the current default timezone.
     *
     * If the configuration value for DateTimeZone::class is already a DateTimeZone instance, it SHALL be returned as-is.
     * Otherwise, the value SHALL be used as the timezone identifier string.
     *
     * @param ContainerInterface $container the container from which to retrieve configuration
     *
     * @return DateTimeZone the resolved DateTimeZone instance
     */
    public function __invoke(ContainerInterface $container): DateTimeZone
    {
        if (! $container->has(ConfigInterface::class)
            || ! $container->get(ConfigInterface::class)->has(DateTimeZone::class)
        ) {
            return new DateTimeZone(date_default_timezone_get());
        }

        $timezone = $container->get(ConfigInterface::class)->get(DateTimeZone::class);

        if ($timezone instanceof DateTimeZone) {
            return $timezone;
        }

        return new DateTimeZone($timezone);
    }
}
