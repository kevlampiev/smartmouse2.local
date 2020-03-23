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
        session_start();
        return parent::__invoke();
    }

    public function postResponse(?array $params = []): string
    {
        return 'No presents for Christmass';
    }

    public function showView(): string
    {
        return TwigService::getTwig()->render(
            'admin/admin_main.twig',
            []
        );
    }
}
