<?php

namespace Smarthouse\Controllers\Admin;


use Smarthouse\Models\Admin\AdminPanelModel;
use Smarthouse\Models\Admin\OrderModel;
use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends BaseAdminController
{
    private $id;

    /**
     * @Route("/admin/orderdetails/{id}", name="adminOrderEdit")
     */
    public function __invoke(?array $parameters): string
    {
        // session_start();
        $this->id = $parameters['id'];
        return parent::__invoke($parameters);
    }

    public function postResponse(?array $params = []): string
    {
        $order = new OrderModel($this->id);
        return json_encode($order->handleOrder($params));
    }

    public function showView(): string
    {
        // $orders = new OrdersListModel();
        $order = new OrderModel($this->id);
        return TwigService::getTwig()->render(
            'admin/order.twig',
            ['order' => $order]
        );
    }
}
