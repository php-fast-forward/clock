ClockServiceProvider
====================

``FastForward\Clock\ServiceProvider\ClockServiceProvider`` registers clock services in Fast Forward Container.

Responsibilities
----------------

- register ``FastForward\Clock\SystemClock`` as the default clock;
- register ``FastForward\Clock\FrozenClock`` for testing scenarios;
- alias ``Psr\Clock\ClockInterface`` to ``SystemClock``;
- provide a ``DateTimeZone`` factory for timezone configuration.

Factory map
-----------

+---------------------------------------------+-----------------------------------------------------------+
| Service ID                                  | Factory behavior                                          |
+=============================================+===========================================================+
| ``FastForward\Clock\SystemClock``           | Creates a system clock with configured timezone           |
+---------------------------------------------+-----------------------------------------------------------+
| ``Psr\Clock\ClockInterface``                | Resolves to the registered SystemClock instance           |
+---------------------------------------------+-----------------------------------------------------------+
| ``FastForward\Clock\FrozenClock``           | Returns the class for manual instantiation                |
+---------------------------------------------+-----------------------------------------------------------+
| ``DateTimeZone``                            | Creates timezone from config, defaults to system TZ       |
+---------------------------------------------+-----------------------------------------------------------+

Extensions
----------

``ClockServiceProvider`` returns an empty extension map. The package does not decorate or mutate the resolved clock after
construction.

Usage example
-------------

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\ServiceProvider\ClockServiceProvider;
   use Psr\Clock\ClockInterface;
   use function FastForward\Container\container;

   $container = container(new ClockServiceProvider());

   $clock = $container->get(ClockInterface::class);
