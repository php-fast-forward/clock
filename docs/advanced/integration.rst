Integration
===========

FastForward Clock is designed to work seamlessly with Fast Forward Container.

The expected integration flow is:

1. register ``ClockServiceProvider`` in the composition root;
2. use ``SystemClock`` in production and ``FrozenClock`` in tests;
3. type-hint ``Psr\Clock\ClockInterface`` in application services for maximum portability.

Example
-------

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\ServiceProvider\ClockServiceProvider;
   use Psr\Clock\ClockInterface;
   use function FastForward\Container\container;

   $container = container(new ClockServiceProvider());

   $clock = $container->get(ClockInterface::class);

Why the package stays simple
----------------------------

This package provides two straightforward clock implementations: ``SystemClock`` for production and ``FrozenClock`` for testing.
Both implement the PSR-20 interface, making them compatible with any PSR-11 container or dependency injection framework.
