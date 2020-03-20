<?php

namespace Smarthouse\Controllers\Customer;

use Smarthouse\Services\TwigService;
use Smarthouse\Models\CustomerModel;
use Smarthouse\Models\OrderModel;
use Symfony\Component\Routing\Annotation\Route;

class MakeOrderController extends BaseCustController
{
    /**
     * @Route("/make_order", name="makeOrder")
     */
    public function __invoke(): string
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return $this->postResponse();
        } else {
            return $this->showView();
        }
    }

    private function showView(): string
    {
        $user = new CustomerModel();
        return TwigService::getTwig()->render(
            'make_order.twig',
            [
                'userInfo' => $user,
            ]
        );
    }

    private function postResponse(): string
    {
        session_start();
        $input = json_decode(file_get_contents("php://input"), true);
        $order = new OrderModel();
        $result = $order->init($input);
        if ($result != []) {
            return json_encode($result);
        }

        return json_encode($order->registerOrder());
    }
}
