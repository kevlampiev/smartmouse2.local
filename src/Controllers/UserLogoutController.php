<?php

namespace Smarthouse\Controllers;

use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;
use Smarthouse\Models\CustomerModel;

class UserLogoutController
{
    /**
     * @Route("/userlogout", name="userlogout")
     */

    public function __invoke(): string
    {
        //примитивная форма жизни. Сделано "на вырост"
        $user = new CustomerModel();
        $user->logOut();
        return "<script>document.location.href='/';</script>";
        // $twig = TwigService::getTwig();
        // return $twig->render('layouts/mainLayout.twig', ['content' => 'good.twig', 'userInfo' => $user]);
    }
}
