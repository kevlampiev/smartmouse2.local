<?php

namespace Smarthouse\Controllers\Customer;

use Smarthouse\Services\TwigService;
use Smarthouse\Models\CustomerModel;
use Symfony\Component\Routing\Annotation\Route;

class MakeOrderController extends BaseCustController
{
    /**
     * @Route("/make_order", name="makeOrder")
     */
    public function __invoke(): string
    {
        $user = new CustomerModel();
        return TwigService::getTwig()->render(
            'make_order.twig',
            [
                'userInfo' => $user,
            ]
        );
    }
}
