<?php

namespace Smarthouse\Models\Admin;

use Smarthouse\Models\SingleGoodModel;
use Smarthouse\Services\DBConnService;

class AdminGoodModel extends SingleGoodModel {
    private $priceList;
    private $categoriesList;

    public function init(int $id): void {
        parent::init($id);

        $sql="SELECT * from good_categories ORDER BY name";
        $this->categoriesList=DBConnService::selectRowsSet($sql);

        $sql="SELECT id,date_open,date_close,price,currency FROM prices WHERE good_id=?";
        $this->priceList=DBConnService::selectRowsSet($sql,[$this->id]);
    }

    public function getPriceList():array {
        return $this->priceList;
    }

    public function getCategoriesList():array {
        return $this->categoriesList;
    }

    public function getGoodCategory():string {
        return $this->categoriesList["{$this->getCategory()}"];
    }
}