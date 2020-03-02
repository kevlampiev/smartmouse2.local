<?php

namespace Smarthouse\Controllers;

use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    /**
     * @Route("/useraccount", name="useraccount")
     */
    public function __invoke(): string
    {
        $twig = TwigService::getTwig();

        return $twig->render('layouts/mainLayout.twig', ['content' => 'userAccPanel.twig', 'id' => 777]);
    }
}
