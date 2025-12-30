<?php

namespace skeleton\src\Controller;

use Gbtux\InertiaBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'], options: ['expose' => true])]
    public function home(): Response
    {
        return $this->inertiaRender('Home', ['name' => 'John Doe']);
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET','POST'], options: ['expose' => true])]
    public function contact(): Response
    {
        return $this->inertiaRender('Contact');
    }

    #[Route('/about-us', name: 'app_about', methods: ['GET'], options: ['expose' => true])]
    public function about(): Response
    {
        return $this->inertiaRender('About');
    }

}