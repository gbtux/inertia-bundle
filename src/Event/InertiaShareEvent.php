<?php

namespace Gbtux\InertiaBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

class InertiaShareEvent extends Event
{
    public function __construct(private array $shares = []) {}

    public function share(string $key, mixed $value): void
    {
        $this->shares[$key] = $value;
    }

    public function getShares(): array
    {
        return $this->shares;
    }
}