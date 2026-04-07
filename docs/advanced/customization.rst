Customization
==============

Custom timezone for SystemClock
-------------------------------

If you want a specific timezone instead of PHP's default timezone, you can configure it in several ways:

**Option 1: Direct instantiation**

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\SystemClock;

   $clock = new SystemClock('America/Sao_Paulo');

**Option 2: Via Fast Forward Config**

If you use Fast Forward Config, the timezone can be configured:

.. code-block:: php

   <?php

   use FastForward\Config\Config;
   use FastForward\Clock\ServiceProvider\ClockServiceProvider;
   use function FastForward\Container\container;

   $config = new Config([
       DateTimeZone::class => 'America/Sao_Paulo',
   ]);

   $container = container($config, new ClockServiceProvider());

**Option 3: Override via service provider**

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\ServiceProvider\ClockServiceProvider;
   use FastForward\Clock\SystemClock;
   use FastForward\Container\ServiceProvider\ArrayServiceProvider;
   use Psr\Clock\ClockInterface;
   use Psr\Container\ContainerInterface;
   use function FastForward\Container\container;

   $customClockProvider = new ArrayServiceProvider([
       SystemClock::class => static fn(ContainerInterface $container): SystemClock => new SystemClock('UTC'),
       ClockInterface::class => static fn(ContainerInterface $container): SystemClock => $container->get(SystemClock::class),
   ]);

   $container = container($customClockProvider, new ClockServiceProvider());

Custom test clock
-----------------

For testing, use ``FrozenClock`` directly or register a custom provider:

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