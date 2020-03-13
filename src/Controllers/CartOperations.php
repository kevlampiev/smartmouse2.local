<?php

namespace Smarthouse\Controllers;

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
        return "response";
    }
}
