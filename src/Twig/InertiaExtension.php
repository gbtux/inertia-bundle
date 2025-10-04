<?php

namespace Gbtux\InertiaBundle\Twig;

use Gbtux\InertiaBundle\Service\InertiaInterface;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFunction;

class InertiaExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('inertia', [$this, 'inertiaFunction']),
            new TwigFunction('inertiaHead', [$this, 'inertiaHeadFunction']),
        ];
    }

    public function inertiaFunction($page)
    {
        return new Markup('<div id="app" data-page="'.htmlspecialchars(json_encode($page)).'"></div>', 'UTF-8');
    }

    public function inertiaHeadFunction($page)
    {
        return new Markup('', 'UTF-8');
    }
}