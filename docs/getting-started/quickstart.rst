Quickstart
==========

This guide helps you get started with FastForward Clock in minutes.

Minimal example: SystemClock
----------------------------

The quickest way to get started is to instantiate ``SystemClock`` directly:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\SystemClock;

   $clock = new SystemClock();

   echo $clock->now()->format(DATE_ATOM) . PHP_EOL;

This returns the current system time. You can optionally specify a timezone:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\SystemClock;

   $clock = new SystemClock('America/Sao_Paulo');

   echo $clock->now()->format('Y-m-d H:i:s P') . PHP_EOL;

Minimal example: FrozenClock
----------------------------

For testing, use ``FrozenClock`` to create deterministic time:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\FrozenClock;

   $clock = new FrozenClock('2026-04-07 10:00:00');

   echo $clock->now()->format(DATE_ATOM) . PHP_EOL;

``FrozenClock`` accepts multiple input formats:

- DateTimeImmutable instances
- Relative strings like ``"next Monday"`` or ``"+1 hour"``
- Unix timestamps (integers or floats)
- Another ClockInterface instance

With Fast Forward Container
---------------------------

For larger applications, use the service provider:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\ServiceProvider\ClockServiceProvider;
   use Psr\Clock\ClockInterface;
   use function FastForward\Container\container;

   $container = container(new ClockServiceProvider());

   $clock = $container->get(ClockInterface::class);

   echo $clock->now()->format(DATE_ATOM) . PHP_EOL;

The resolved instance is ``FastForward\Clock\SystemClock``.
