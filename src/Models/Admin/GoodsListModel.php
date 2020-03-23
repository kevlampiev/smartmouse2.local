<?php

namespace Smarthouse\Models\Admin;

use Smarthouse\Services\DBConnService;

class GoodsListModel
{

    private $goodsList;

    public function __construct()
    {
        $sql = 'SELECT * FROM v_goods_all';
        $this->goodsList = DBConnService::selectRowsSet($sql);
    }

    public function getGoodsList(): array
    {
        return $this->goodsList;
    }
}
