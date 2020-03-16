<?php

namespace Smarthouse\Controllers\Customer;

use Smarthouse\Services\TwigService;
use Smarthouse\Models\GoodsSetModel;
use Smarthouse\Models\CustomerModel;
use Symfony\Component\Routing\Annotation\Route;

class CartOperations
{


    /**
     * @Route("/cart_operations", name="cart_operations")
     */
    public function __invoke(array $params = null): string
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return $this->cartOperationRequest();
        } else {
            return $this->showView();
        }
    }

    public function cartOperationRequest():string {

        
        retun "qq";
    }
}
