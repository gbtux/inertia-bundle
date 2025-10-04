<?php

namespace Gbtux\InertiaBundle\Service;

use Gbtux\InertiaBundle\Contract\RoutingHistoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class RoutingHistory implements RoutingHistoryInterface
{
    /** @var Request|null  */
    private $request;

    /** @var string  */
    private $previousUrlSessionKey;

    public function __construct(RequestStack $requestStack, string $previousUrlSessionKey)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->previousUrlSessionKey = $previousUrlSessionKey;
    }
        /**
         * Get the URL for the previous request.
         */
        public function getPreviousUrl(string $fallback = null): string
    {
        $referrer = $this->request->headers->get('referer');

        $url = $referrer ?: $this->getPreviousUrlFromSession();

        return $url ?? $fallback ?? '/';
    }

        /**
         * Get the previous URL from the session if possible.
         */
        private function getPreviousUrlFromSession(): ?string
    {
        return $this->request->getSession()->get($this->previousUrlSessionKey);
    }

}