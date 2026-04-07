FAQ
===

1. What clocks does this package provide?

   FastForward Clock provides two PSR-20 compliant implementations:
   
   - ``SystemClock``: Returns the current system time (ideal for production)
   - ``FrozenClock``: Returns a fixed time (ideal for testing)

2. Which clock should I use in production?

   Use ``SystemClock`` or request ``Psr\Clock\ClockInterface`` from the container.

3. Which interface should application services depend on?

   Prefer ``Psr\Clock\ClockInterface`` so the service contract remains portable across different clock implementations.

4. How do I freeze time in tests?

   Use ``FrozenClock`` directly, or register it via an ``ArrayServiceProvider`` before ``ClockServiceProvider``.

5. Can I use this package without Fast Forward Container?

   Yes! Both ``SystemClock`` and ``FrozenClock`` are standalone classes that can be instantiated directly:

   .. code-block:: php

      $clock = new FastForward\Clock\FrozenClock('2026-04-07 10:00:00');

6. Does this package require Fast Forward Container?

   No. The clocks work independently. The service provider is optional for container integration.

7. How do I set a specific timezone?

   Pass a timezone string or ``DateTimeZone`` instance to the ``SystemClock`` constructor:

   .. code-block:: php

      $clock = new SystemClock('America/Sao_Paulo');

8. Can I create my own clock implementation?

   Yes. Both clocks implement ``Psr\Clock\ClockInterface``, so you can create any implementation that satisfies this interface.

9. What's the difference between FrozenClock and MockClock?

   ``FrozenClock`` is a simple, standalone class that always returns the same time. It's perfect for most testing scenarios.
   If you need more advanced features like mocking ``sleep()`` calls, consider using Symfony's ``MockClock`` directly.

10. Why use this package instead of creating my own clock?

    This package provides well-tested, PSR-20 compliant implementations that work seamlessly with Fast Forward Container
    and any PSR-11 compatible container. It also provides the service provider for easy integration.