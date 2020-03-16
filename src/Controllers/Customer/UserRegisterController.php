<?php

namespace Smarthouse\Controllers\Customer;

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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return $this->postResonce();
        } else {
            return $this->showView();
        }
    }

    private function postResonce()
    {
        $user = new CustomerModel();
        $user->readPostRequest();
        $errorsList = $user->dataUserErrors();
        if ($errorsList != '') {
            $twig = TwigService::getTwig();
            $result = $twig->render('registerUser.twig', ['document' => 'Smarthouse registration', 'userInfo' => $user, 'errors' => $errorsList, 'displayErr' => true]);
        } else {
            $user->registerNewUser();
            $result = "<script>document.location.href='/';</script>";
        }
        return $result;
    }

    private function showView()
    {
        $user = new CustomerModel();
        $user->registerNewUser();
        $twig = TwigService::getTwig();
        return $twig->render('registerUser.twig', ['document' => 'Smarthouse registration', 'userInfo' => $user, 'errors' => "", 'displayErr' => false]);
    }
}
