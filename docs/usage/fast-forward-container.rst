Fast Forward Container
======================

This package is primarily about container integration.

Register the service provider once in your composition root and let application services depend on the PSR-20 interface.

.. code-block:: php

   <?php

   declare(strict_types=1);

   use FastForward\Clock\ServiceProvider\ClockServiceProvider;
   use Psr\Clock\ClockInterface;
   use function FastForward\Container\container;

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

   $container = container(new ClockServiceProvider());

   $factory = $container->get(TokenFactory::class);

Autowiring and aliases
----------------------

Fast Forward Container can autowire ``TokenFactory`` because the provider contributes a concrete service for
``Psr\Clock\ClockInterface``.

The same container can also resolve:

- ``Symfony\Component\Clock\NativeClock``
- ``Psr\Clock\ClockInterface``
- ``Symfony\Component\Clock\ClockInterface``

All three identifiers point to the same production clock instance.
