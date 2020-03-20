<?php

namespace Smarthouse\Controllers\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Smarthouse\Models\UserModel;

class AdminLoginController
{
    /**
     * @Route("/admin/login", name="adminlogin")
     */

    public function __invoke(): string
    {
        //примитивная форма жизни
        $user = new UserModel();

        // $input = json_decode(file_get_contents("php://input"), true);

        $log = strip_tags((string) $_POST["login"]);
        $pass = strip_tags((string) $_POST["password"]);
        
        $response = $user->logIn($log, $pass,'');
        return json_encode($response);
    }
}
