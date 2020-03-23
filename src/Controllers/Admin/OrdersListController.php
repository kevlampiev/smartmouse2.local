<?php

namespace Smarthouse\Controllers\Admin;


use Smarthouse\Models\Admin\AdminPanelModel;
use Smarthouse\Models\Admin\OrdersListModel;
use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

class OrdersListController extends BaseAdminController
{


    /**
     * @Route("/admin/orders", name="adminGoods")
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
        $orders = new OrdersListModel();
        return TwigService::getTwig()->render(
            'admin/orders_list.twig',
            ['orders' => $orders]
        );
    }
}
