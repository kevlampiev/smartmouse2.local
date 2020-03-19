<?php

namespace Smarthouse\Controllers\Admin;

use Smarthouse\Models\CategoriesModel;
use Smarthouse\Models\UserModel;
use Smarthouse\Services\DBConnService;
use Smarthouse\Services\TwigService;

class AdminPanelController extends BaseAdminController
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
        $twig = TwigService::getTwig();
        return $twig->render(
            'layouts/admin_layout.twig',
            []
        );
    }
}
