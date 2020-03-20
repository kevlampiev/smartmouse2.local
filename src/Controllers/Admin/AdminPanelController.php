<?php

namespace Smarthouse\Controllers\Admin;


use Smarthouse\Models\CartModel;
use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController extends BaseAdminController
{


    /**
     * @Route("/admin", name="admin")
     */
    public function __invoke(): string
    {
        return parent::__invoke();
    }

    public function showView(): string
    {
        return TwigService::getTwig()->render(
            'admin/admin_main.twig',
            []
        );
    }
}
