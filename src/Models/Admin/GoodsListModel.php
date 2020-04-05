<?php

namespace Smarthouse\Models\Admin;

use Smarthouse\Services\DBConnService;

class GoodsListModel
{

    private $goodsList;

    public function __construct()
    {
        $this->clearFilter();
    }

    public function getGoodsList(): array
    {
        return $this->goodsList;
    }

    public function setFilter(array $filterParams): void
    {
        $titleFilter = '%' . $filterParams['title'] . '%';
        $categoryFilter = '%' . $filterParams['categoty'] . '%';
        $priceFromFilter = $filterParams['priceFrom'];
        $priceFromFilter = $filterParams['priceTo'];
        $currencyFilter = '%' . $filterParams['currency'] . '%';

        $sql = "SELECT * FROM v_available_goods 
                WHERE name LIKE ? AND
                category LIKE ? AND
                price BETWEEN ? AND ? AND
                currency LIKE ?";
        $this->goodsList = DBConnService::selectRowsSet($sql, [$titleFilter, $categoryFilter, $priceFromFilter, $priceFromFilter, $currencyFilter]);
    }

    public function clearFilter(): void
    {
        $sql = 'SELECT * FROM v_available_goods';
        $this->goodsList = DBConnService::selectRowsSet($sql);
    }

    public function handleGoodsList(array $params): array
    {
        switch ($params['action']) {
            case 'setFilter':
                $res = $this->setFilter($params);
                break;
            case 'clearFilter':
                $res = $this->clearFilter();
                break;
            default:
                $res = ['status' => "Error: the action {$params['action']} doesn't exist..."];
        }

        return $res;
    }
}
