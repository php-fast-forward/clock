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

namespace FastForward\Clock\Tests\ServiceProvider\Factory;

use DateTimeZone;
use FastForward\Clock\ServiceProvider\Factory\DateTimeZoneFactory;
use FastForward\Config\ConfigInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;

#[CoversClass(DateTimeZoneFactory::class)]
class DateTimeZoneFactoryTest extends TestCase
{
    use ProphecyTrait;

    private ObjectProphecy $config;

    private ObjectProphecy $container;

    private DateTimeZoneFactory $factory;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->config = $this->prophesize(ConfigInterface::class);
        $this->container = $this->prophesize(ContainerInterface::class);

        $this->factory = new DateTimeZoneFactory();
    }

    /**
     * @return void
     */
    #[Test]
    public function testReturnsConfiguredDateTimeZoneInstance(): void
    {
        $timezone = new DateTimeZone('Europe/Lisbon');
        $this->config->has(DateTimeZone::class)->willReturn(true);
        $this->config->get(DateTimeZone::class)->willReturn($timezone);

        $this->container->has(ConfigInterface::class)->willReturn(true);
        $this->container->get(ConfigInterface::class)->willReturn($this->config->reveal());

        $result = ($this->factory)($this->container->reveal());
        self::assertSame($timezone, $result);
    }

    /**
     * @return void
     */
    #[Test]
    public function testReturnsDateTimeZoneFromConfiguredString(): void
    {
        $this->config->has(DateTimeZone::class)->willReturn(true);
        $this->config->get(DateTimeZone::class)->willReturn('America/Sao_Paulo');

        $this->container->has(ConfigInterface::class)->willReturn(true);
        $this->container->get(ConfigInterface::class)->willReturn($this->config->reveal());

        $result = ($this->factory)($this->container->reveal());
        self::assertInstanceOf(DateTimeZone::class, $result);
        self::assertSame('America/Sao_Paulo', $result->getName());
    }

    /**
     * @return void
     */
    #[Test]
    public function testReturnsDefaultDateTimeZoneWhenNoConfig(): void
    {
        $this->container->has(ConfigInterface::class)->willReturn(false);

        $result = ($this->factory)($this->container->reveal());
        self::assertInstanceOf(DateTimeZone::class, $result);
        self::assertSame(date_default_timezone_get(), $result->getName());
    }

    /**
     * @return void
     */
    #[Test]
    public function testReturnsDefaultDateTimeZoneWhenConfigHasNoTimezone(): void
    {
        $this->config->has(DateTimeZone::class)->willReturn(false);

        $this->container->has(ConfigInterface::class)->willReturn(true);
        $this->container->get(ConfigInterface::class)->willReturn($this->config->reveal());

        $result = ($this->factory)($this->container->reveal());
        self::assertInstanceOf(DateTimeZone::class, $result);
        self::assertSame(date_default_timezone_get(), $result->getName());
    }
}
