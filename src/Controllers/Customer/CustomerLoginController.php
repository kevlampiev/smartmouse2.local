<?php

namespace Smarthouse\Controllers\Customer;

//use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;
use Smarthouse\Models\CustomerModel;

class CustomerLoginController
{
    /**
     * @Route("/userlogin", name="userlogin")
     */

    public function __invoke(): string
    {
        //примитивная форма жизни
        $user = new CustomerModel();

        $input = json_decode(file_get_contents("php://input"), true);

        $log = strip_tags((string) $input["login"]);
        $pass = strip_tags((string) $input["password"]);
        $rememberMe = strip_tags((string) $input["rememberMe"]);

        $response = $user->logIn($log, $pass, $rememberMe);
        return json_encode($response);
    }
}
