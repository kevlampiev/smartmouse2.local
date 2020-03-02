<?php

namespace Smarthouse\Controllers;

use Smarthouse\Services\TwigService;
use Smarthouse\Models\UserModel;
use Symfony\Component\Routing\Annotation\Route;

class BaseController
{
    /**
     * @Route("/", name="base")
     */
    public function __invoke(): string
    {
        $twig = TwigService::getTwig();
        $user = new UserModel();
        return $twig->render('layouts/mainLayout.twig', ['content' => 'good.twig', 'userInfo' => $user]);
    }
}
