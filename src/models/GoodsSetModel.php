<?php


namespace Smarthouse\Models;

use Smarthouse\Services\DBConnService;

//Вообще нет идей, зачем это все надо
class GoodsSetModel
{
    public function getGoodsOfCategory(int $categoryId): array
    {
        $sql = "SELECT * FROM v_available_goods WHERE category_id=?";
        return DBConnService::selectRowsSet($sql, [$categoryId]);
    }
}
