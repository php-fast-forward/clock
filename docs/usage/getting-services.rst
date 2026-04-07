Getting Services
================

This section explains how to retrieve clock services in your application.

Direct instantiation
--------------------

You can use clock classes directly without any container:

+------------------------------------------+-----------------------------------------------------------+
| Class                                    | Description                                               |
+==========================================+===========================================================+
| ``FastForward\Clock\SystemClock``        | Returns current system time with optional timezone        |
+------------------------------------------+-----------------------------------------------------------+
| ``FastForward\Clock\FrozenClock``        | Returns fixed time for deterministic testing              |
+------------------------------------------+-----------------------------------------------------------+
| ``Psr\Clock\ClockInterface``             | Interface implemented by both clock classes               |
+------------------------------------------+-----------------------------------------------------------+

Example with SystemClock:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\SystemClock;

   $clock = new SystemClock('America/New_York');

   echo $clock->now()->format(DATE_ATOM) . PHP_EOL;

Example with FrozenClock:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\FrozenClock;

   $clock = new FrozenClock('2026-04-07 10:00:00');

   echo $clock->now()->format(DATE_ATOM) . PHP_EOL;

Via Fast Forward Container
--------------------------

``ClockServiceProvider`` exposes the following service identifiers:

+---------------------------------------------+-----------------------------------------------------------+
| Service ID                                  | Resolved instance                                         |
+=============================================+===========================================================+
| ``FastForward\Clock\SystemClock``           | A system clock created by the provider                    |
+---------------------------------------------+-----------------------------------------------------------+
| ``Psr\Clock\ClockInterface``                | Alias to the registered ``SystemClock`` instance          |
+---------------------------------------------+-----------------------------------------------------------+
| ``FastForward\Clock\FrozenClock``           | A frozen clock factory (not pre-instantiated)             |
+---------------------------------------------+-----------------------------------------------------------+

You can retrieve any of them from the container:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\ServiceProvider\ClockServiceProvider;
   use Psr\Clock\ClockInterface;
   use FastForward\Clock\SystemClock;
   use FastForward\Clock\FrozenClock;
   use function FastForward\Container\container;

   $container = container(new ClockServiceProvider());

   $systemClock = $container->get(SystemClock::class);
   $psrClock = $container->get(ClockInterface::class);
   $frozenClock = $container->get(FrozenClock::class);

   assert($systemClock === $psrClock);

Prefer ``Psr\Clock\ClockInterface`` in application services for maximum portability. Use specific implementations when you need
particular features like timezone control or time freezing.
