<?php

namespace Smarthouse\Controllers;

use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;
use Smarthouse\Models\UserModel;

class UserController
{

    /**
     * @Route("/useraccount", name="useraccount")
     */
    public function __invoke(): string
    {
        $twig = TwigService::getTwig();
        $user = new UserModel();
        return $twig->render(
            'layouts/mainLayout.twig',
            ['content' => 'userAccPanel.twig',  'userInfo' => $user]
        );
    }
}
