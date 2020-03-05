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

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user->readPostRequest();
            $errorsList = $user->dataUserErrors();
            if ($errorsList != '') {
                $twig = TwigService::getTwig();
                $result = $twig->render('registerUser.twig', ['document' => 'Smarthouse registration', 'userInfo' => $user, 'errors' => $errorsList, 'displayErr' => true]);
            } else {
                $user->registerNewUser();
                $result = "<script>document.location.href='/';</script>";
            }
        } else {
            $twig = TwigService::getTwig();
            $result = $twig->render('registerUser.twig', ['document' => 'Smarthouse registration', 'userInfo' => $user, 'errors' => "", 'displayErr' => false]);
        }
        return $result;
    }
}
