Testing with FrozenClock
========================

FastForward Clock provides ``FrozenClock`` specifically for testing scenarios where you need deterministic, reproducible time.

Unlike system time which changes every second, ``FrozenClock`` returns a fixed time that you control.

Direct usage in tests
---------------------

The simplest way to use deterministic time in tests:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\FrozenClock;

   $clock = new FrozenClock('2026-04-07 10:00:00');

   echo $clock->now()->format(DATE_ATOM) . PHP_EOL;
   // Output: 2026-04-07T10:00:00+00:00

With Fast Forward Container
--------------------------

To use ``FrozenClock`` with Fast Forward Container in your tests, register a provider that exposes your frozen clock before
``ClockServiceProvider``:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\ServiceProvider\ClockServiceProvider;
   use FastForward\Clock\FrozenClock;
   use FastForward\Container\ServiceProvider\ArrayServiceProvider;
   use Psr\Clock\ClockInterface;
   use Psr\Container\ContainerInterface;
   use function FastForward\Container\container;

   $frozenClock = new FrozenClock('2026-04-07 10:00:00');

   $testProvider = new ArrayServiceProvider([
       FrozenClock::class => static fn(ContainerInterface $container): FrozenClock => $frozenClock,
       ClockInterface::class => static fn(ContainerInterface $container): FrozenClock => $container->get(FrozenClock::class),
   ]);

   $container = container($testProvider, new ClockServiceProvider());

   $clock = $container->get(ClockInterface::class);

   echo $clock->now()->format(DATE_ATOM) . PHP_EOL;

Why the provider order matters
------------------------------

Fast Forward Container resolves services from earlier initializers first. Registering the test provider before
``ClockServiceProvider`` ensures that the PSR interface points to your ``FrozenClock`` instead of the default
``SystemClock``.

FrozenClock input formats
-------------------------

``FrozenClock`` accepts multiple input types:

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\FrozenClock;
   use FastForward\Clock\SystemClock;

   // From a string (relative or absolute)
   $clock1 = new FrozenClock('2026-04-07 10:00:00');
   $clock2 = new FrozenClock('next Monday');
   $clock3 = new FrozenClock('+1 hour');

   // From a DateTimeImmutable
   $clock4 = new FrozenClock(new \DateTimeImmutable('2026-04-07 10:00:00'));

   // From a timestamp (integer or float)
   $clock5 = new FrozenClock(1775640000);

   // From another ClockInterface
   $clock6 = new FrozenClock(new SystemClock('America/New_York'));
