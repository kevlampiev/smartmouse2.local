<?php

namespace Smarthouse\Controllers\Admin;


use Smarthouse\Models\Admin\AdminPanelModel;
use Smarthouse\Models\Admin\GoodsListModel;
use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;
use Smarthouse\Models\Admin\AdminGoodModel;

class EditGoodController extends BaseAdminController
{
    private $params;

    /**
     * @Route("/admin/goodedit/{id}", name="adminGoodEdit")
     */
    public function __invoke(?array $parameters): string
    {
        // session_start();
        $this->params=$parameters;
        return parent::__invoke($parameters);
    }

    public function postResponse(?array $params = []): string
    {
        return 'No presents for Christmass';
    }

    public function showView(): string
    {

        $good=new AdminGoodModel($this->params['id']);

        return TwigService::getTwig()->render(
            'admin/good_edit.twig',
            ['good'=>$good]
        );
    }
}
