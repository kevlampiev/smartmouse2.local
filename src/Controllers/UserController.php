<?php

namespace Smarthouse\Controllers;

use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;
use Smarthouse\Models\CustomerModel;

class UserController
{

    /**
     * @Route("/useraccount", name="useraccount")
     */
    public function __invoke(): string
    {
        $twig = TwigService::getTwig();
        $user = new CustomerModel();
        return $twig->render(
            'layouts/mainLayout.twig',
            ['content' => 'userAccPanel.twig',  'userInfo' => $user]
        );
    }
}
