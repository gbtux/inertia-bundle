<?php

namespace Gbtux\InertiaBundle\EventListener;

use Gbtux\InertiaBundle\Service\InertiaInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class InertiaSubscriber implements EventSubscriberInterface
{
    public function __construct(private InertiaInterface $inertia)
    {}

    public function onKernelController(ControllerEvent $event): void
    {
        $this->inertia->share(
            'errors',
            fn () => $event->getRequest()->getSession()->remove('errors')
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}