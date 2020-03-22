<?php

namespace Smarthouse\Controllers\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Smarthouse\Models\AdminModel;

class AdminLoginController
{
    /**
     * @Route("/admin/login/{id}", name="adminlogin")
     */

    public function __invoke(): string
    {
        //примитивная форма жизни
        $user = new AdminModel();

        // $input = json_decode(file_get_contents("php://input"), true);

        $log = strip_tags((string) $_POST["login"]);
        $pass = strip_tags((string) $_POST["password"]);

        $response = $user->logIn($log, $pass, '');
        return "header('/admin');";
        // $_SERVER['HTTP_REFERER']
        // header("Location:login.php");
    }
}
