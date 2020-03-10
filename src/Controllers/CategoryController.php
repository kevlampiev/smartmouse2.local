<?php

namespace Smarthouse\Controllers;

use Smarthouse\Services\TwigService;
use Smarthouse\Models\GoodsSetModel;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends BaseCustController
{
    /**
     * @Route("/category/{id}", name="categories")
     */
    public function __invoke(array $params): string
    {
        $goods = new GoodsSetModel();
        $twig = TwigService::getTwig();
        return $twig->render(
            'layouts/mainLayout.twig',
            [
                'content' => 'goodsOfCategory.twig',
                'categories' => $this->getCategories(),
                'selected_id' => $params['id'],
                'goodListTytle' => 'Goods of this category',
                'goods' => $goods->getGoodsOfCategory((int) $params['id'])
            ]
        );
    }
}
