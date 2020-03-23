<?php

namespace Smarthouse\Controllers\Admin;


use Smarthouse\Models\Admin\AdminPanelModel;
use Smarthouse\Models\Admin\GoodsListModel;
use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

class GoodsListController extends BaseAdminController
{


    /**
     * @Route("/admin/goods", name="adminGoods")
     */
    public function __invoke(): string
    {
        session_start();
        return parent::__invoke();
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
