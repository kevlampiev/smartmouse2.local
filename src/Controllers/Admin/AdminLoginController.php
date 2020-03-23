<?php

namespace Smarthouse\Controllers\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Smarthouse\Models\AdminModel;
use Smarthouse\Services\TwigService;

class AdminLoginController
{
    /**
     * @Route("/admin/login", name="adminlogin")
     */

    public function __invoke(): string
    {
        $user = new AdminModel();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $log = $_POST['login'];
            $pass = $_POST['password'];
            $logintResult = $user->logIn($log, $pass, null);

            if (array_key_exists('error', $logintResult)) {
                $result = TwigService::getTwig()->render('admin/admin_login.twig', [$user]);
            } else {
                $result = "<html><head><META HTTP-EQUIV='Refresh' content='0; URL=/admin'></head>
                <body></body></html>";
            }
        } else {
            $result = TwigService::getTwig()->render('admin/admin_login.twig', [$user]);
        }
        return $result;
    }
}
