<?php

namespace Smarthouse\Controllers;

use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

class BaseController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function __invoke(): string
    {
        //$twig = TwigService::getTwig();
        //return $twig->render('layouts/mainLayout.twig', ['content' => 'good.twig', 'id' => 777]);
        return "";
    }
}
