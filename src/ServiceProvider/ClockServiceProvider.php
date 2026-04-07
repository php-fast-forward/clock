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

namespace FastForward\Clock\ServiceProvider;

use DateTimeZone;
use FastForward\Clock\ServiceProvider\Factory\DateTimeZoneFactory;
use FastForward\Clock\SystemClock;
use FastForward\Container\Factory\AliasFactory;
use FastForward\Container\Factory\InvokableFactory;
use Interop\Container\ServiceProviderInterface;
use Psr\Clock\ClockInterface;

/**
 * Registers Symfony Clock services for Fast Forward Container.
 */
/**
 * Service provider for registering Symfony Clock services in the Fast Forward Container.
 *
 * This class SHALL expose factories and extensions for clock-related services, including
 * PSR-20 and Symfony Clock implementations, using the Fast Forward Container conventions.
 *
 * @see https://github.com/php-fast-forward/clock
 * @see https://github.com/php-fast-forward
 * @see https://datatracker.ietf.org/doc/html/rfc2119
 */
final readonly class ClockServiceProvider implements ServiceProviderInterface
{
    /**
     * Returns the service factories exposed by this package.
     *
     * The returned array SHALL map service names (class strings) to callables or factory objects
     * that create the corresponding service instances. This includes:
     *
     * - DateTimeZone::class: Provides a DateTimeZone instance using DateTimeZoneFactory.
     * - ClockInterface::class and SymfonyClockInterface::class: Aliases to NativeClock.
     * - NativeClock, MockClock, MonotonicClock: Instantiated via InvokableFactory with appropriate arguments.
     *
     * @see https://github.com/php-fast-forward/clock
     *
     * @return array<string, callable> associative array of service factories
     */
    public function getFactories(): array
    {
        return [
            ClockInterface::class => AliasFactory::get(SystemClock::class),
            SystemClock::class => new InvokableFactory(SystemClock::class, DateTimeZone::class),
            DateTimeZone::class => new DateTimeZoneFactory(),
        ];
    }

    /**
     * Returns the service extensions exposed by this package.
     *
     * The returned array SHALL map service names (class strings) to callables that extend or decorate
     * existing services. This implementation returns an empty array, as no extensions are provided by default.
     *
     * @see https://github.com/php-fast-forward/clock
     *
     * @return array<string, callable> associative array of service extensions (empty by default)
     */
    public function getExtensions(): array
    {
        return [];
    }
}
