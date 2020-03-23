<?php

namespace Smarthouse\Controllers\Admin;


use Smarthouse\Models\Admin\AdminPanelModel;
use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController extends BaseAdminController
{


    /**
     * @Route("/admin", name="admin")
     */
    public function __invoke(?array $parameters): string
    {
        // session_start();
        return parent::__invoke($parameters);
    }

    public function postResponse(?array $params = []): string
    {
        return 'No presents for Christmass';
    }

    public function showView(): string
    {
        $admPanelData = new AdminPanelModel();
        return TwigService::getTwig()->render(
            'admin/admin_main.twig',
            ['admPanelInfo' => $admPanelData]
        );
    }
}
