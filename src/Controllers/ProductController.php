<?php

namespace Smarthouse\Controllers;

use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

class ProductController
{
    /**
     * @Route("/good/{id}", name="good")
     */
    public function __invoke(array $parameters): string
    {
        $twig = TwigService::getTwig();
        return $twig->render('good.twig', ['id' => $parameters['id']]);
    }
}
