<?php

namespace Smarthouse\Controllers;

use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;
use Smarthouse\Models\CustomerModel;

class UserRegisterController
{
    /**
     * @Route("/user-register", name="user-register")
     */

    public function __invoke(): string
    {

        $user = new CustomerModel();
        // $user->logOut();
        // return "<script>document.location.href='/';</script>";
        $twig = TwigService::getTwig();
        return $twig->render('registerUser.twig', ['document' => 'Smarthouse registration', 'userInfo' => $user]);
    }
}
