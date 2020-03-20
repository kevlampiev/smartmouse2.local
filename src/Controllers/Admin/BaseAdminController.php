<?php

namespace Smarthouse\Controllers\Admin;


use Smarthouse\Models\CartModel;
use Smarthouse\Models\UserModel;
use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

abstract class BaseAdminController
{
    private $user;

    public function __construct()
    {
        $this->user=new UserModel();    
    }

    public function __invoke(): string
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

    public function askPassword():string {
        return TwigService::getTwig()->render('admin/admin_login.twig',[]);
    }

    public function postResponse(array $params=[]):string {
        return '';
    }

    protected function showView(): string
    {
        return TwigService::getTwig()->render(
            'admin/admin_main.twig',
            []
        );
    }
}
