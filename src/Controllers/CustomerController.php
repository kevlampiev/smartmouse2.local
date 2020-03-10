<?php

namespace Smarthouse\Controllers;

use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;
use Smarthouse\Models\CustomerModel;

class CustomerController
{

    /**
     * @Route("/useraccount", name="useraccount")
     */
    public function __invoke(): string
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return $this->editField();
        } else {
            return $this->showView();
        }
    }

    public function showView(): string
    {
        $twig = TwigService::getTwig();
        $user = new CustomerModel();
        return $twig->render(
            'layouts/mainLayout.twig',
            ['content' => 'userAccPanel.twig',  'userInfo' => $user]
        );
    }

    public function editField(): string
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $action = $input['action'];
        $fieldName = $input['fieldName'];
        $newValue = $input['value'];
        $user = new CustomerModel();

        if ($action == "editUserInfo") {
            switch ($fieldName) {
                case "password":
                    $pass = $input['currentPassword'];
                    $res = $user->updatePassword($pass, $newValue);
                    break;
                default:
                    $res = $user->updateUserField($fieldName, $newValue);
            }
        }
        return json_encode($res);;
    }
}
