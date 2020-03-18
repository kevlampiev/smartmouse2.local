<?php

namespace Smarthouse\Controllers\Customer;


use Smarthouse\Models\CartModel;
use Symfony\Component\Routing\Annotation\Route;

class CartOperationsController
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

    public function showView(): string
    {
        return "<h1> No presents for Christmass </h1>";
    }

    public function cartOperationRequest(): string
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $action = $input['action'];
        $item = array_key_exists('item', $input)?$input['item']:null;
        
        $cart=new CartModel();       
        return json_encode($cart->handleCart($action, $item));
    }
}
