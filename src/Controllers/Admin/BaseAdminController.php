<?php

namespace Smarthouse\Controllers\Admin;


use Smarthouse\Models\CartModel;
use Smarthouse\Models\AdminModel;
use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

abstract class BaseAdminController
{
    private $user;

    public function __construct()
    {
        session_start();
        $this->user = new AdminModel();
    }

    public function __invoke(?array $parameters): string
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $input = json_decode(file_get_contents("php://input"), true);
            return $this->postResponse($input);
        } else {
            if (!$this->user->getIsLogged()) {
                return $this->askPassword();
            }
            return $this->showView();
        }
    }

    public function askPassword(): string
    {
        return "<html><head><META HTTP-EQUIV='Refresh' content='0; URL=/admin/login'></head>
        <body></body></html>";
    }

    abstract public function postResponse(?array $params = []): string;


    abstract protected function showView(): string;
}
