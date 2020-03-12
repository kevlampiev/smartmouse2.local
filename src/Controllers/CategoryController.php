<?php

namespace Smarthouse\Controllers;

use Smarthouse\Services\TwigService;
use Smarthouse\Models\GoodsSetModel;
use Smarthouse\Models\CustomerModel;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends BaseCustController
{
    /**
     * @Route("/category/{id}", name="categories")
     */
    public function __invoke(array $params): string
    {
        $categorySet = new GoodsSetModel();
        $goods = $categorySet->getGoodsOfCategory((int) $params['id']);
        if ($goods == []) {
            return TwigService::getTwig()->render('layouts/not_found.twig', []);
        }
        $user = new CustomerModel();
        return TwigService::getTwig()->render(
            'goods_of_category.twig',
            [
                'selected_id' => $params['id'],
                'goodListTytle' => 'Goods of this category',
                'userInfo' => $user,
                'goods' => $goods
            ]
        );
    }
}
