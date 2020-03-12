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
        $goods = new GoodsSetModel();
        return TwigService::getTwig()->render(
            'goods_of_category.twig',
            array_merge([
                'selected_id' => $params['id'],
                'goodListTytle' => 'Goods of this category',
                'goods' => $goods->getGoodsOfCategory((int) $params['id'])
            ], $this->baseViewData())
        );
    }
}
