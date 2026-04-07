Use Cases
=========

This section shows common usage patterns for FastForward Clock.

Inject a PSR-20 clock into application services
-----------------------------------------------

.. code-block:: php

   <?php

   declare(strict_types=1);

   use Psr\Clock\ClockInterface;

   final readonly class TokenFactory
   {
       public function __construct(private ClockInterface $clock)
       {
       }

       public function expiresAt(): \DateTimeImmutable
       {
           return $this->clock->now()->add(new \DateInterval('PT15M'));
       }
   }

Create a SystemClock with specific timezone
--------------------------------------------

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\SystemClock;

   $clock = new SystemClock('America/Sao_Paulo');

   echo $clock->now()->format('Y-m-d H:i:s P') . PHP_EOL;

Use FrozenClock for testing
---------------------------

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\FrozenClock;

   $clock = new FrozenClock('2026-04-07 10:00:00');

   // In tests, this time remains constant
   echo $clock->now()->format(DATE_ATOM) . PHP_EOL;

Resolve the production clock from Fast Forward Container
---------------------------------------------------------

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\ServiceProvider\ClockServiceProvider;
   use Psr\Clock\ClockInterface;
   use function FastForward\Container\container;

   $container = container(new ClockServiceProvider());
   $clock = $container->get(ClockInterface::class);

Replace the production clock with FrozenClock in tests
------------------------------------------------------

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
