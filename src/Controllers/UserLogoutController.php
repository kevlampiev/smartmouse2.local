<?php

namespace Smarthouse\Controllers;

use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;
use Smarthouse\Models\UserModel;

class UserLogoutController
{
    /**
     * @Route("/userlogout", name="userlogout")
     */

    public function __invoke(): string
    {
        //примитивная форма жизни. Сделано "на вырост"
        $user = new UserModel();
        $user->logOut();
        $twig = TwigService::getTwig();
        return $twig->render('layouts/mainLayout.twig', ['content' => 'good.twig', 'userInfo' => $user]);
    }
}
