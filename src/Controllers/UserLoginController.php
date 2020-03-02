<?php

namespace Smarthouse\Controllers;

//use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;
use Smarthouse\Models\UserModel;

class UserLoginController
{
    /**
     * @Route("/userlogin", name="userlogin")
     */

    public function __invoke(): string
    {
        //примитивная форма жизни
        $user = new UserModel();

        $input = json_decode(file_get_contents("php://input"), true);

        $log = strip_tags((string) $input["login"]);
        $pass = strip_tags((string) $input["password"]);
        $rememberMe = strip_tags((string) $input["rememberMe"]);

        $response = $user->logIn($log, $pass, $rememberMe);
        return json_encode($response);
    }
}
