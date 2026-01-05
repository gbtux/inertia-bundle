<?php

namespace Gbtux\InertiaBundle\EventListener;

use Gbtux\InertiaBundle\Event\InertiaShareEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class FlashMessagesListener
{
    public function __construct(private RequestStack $requestStack) {}

    public function onInertiaShare(InertiaShareEvent $event): void
    {
        $session = $this->requestStack->getSession();
        $event->share('flash', $session->getFlashBag()->all());
    }
}