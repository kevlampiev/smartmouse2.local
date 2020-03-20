<?php

namespace Smarthouse\Controllers\Admin;


use Smarthouse\Models\CartModel;
use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController
{


    /**
     * @Route("/admin", name="admin")
     */
    public function __invoke(): string
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return [];
        } else {
            return $this->showView();
        }
    }

    public function showView(): string
    {
        return TwigService::getTwig()->render(
            'admin/admin_main.twig',
            []
        );
    }
}
