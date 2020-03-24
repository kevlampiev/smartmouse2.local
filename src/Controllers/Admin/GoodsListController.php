<?php

namespace Smarthouse\Controllers\Admin;


use Smarthouse\Models\Admin\GoodsListModel;
use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

class GoodsListController extends BaseAdminController
{


    /**
     * @Route("/admin/goods", name="adminGoods")
     */
    public function __invoke(?array $parameters): string
    {
        return parent::__invoke($parameters);
    }

    public function postResponse(?array $params = []): string
    {
        return 'No presents for Christmass';
    }

    public function showView(): string
    {
        $goods = new GoodsListModel();
        return TwigService::getTwig()->render(
            'admin/goods_list.twig',
            ['goods' => $goods]
        );
    }
}
