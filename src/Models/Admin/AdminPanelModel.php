<?php

namespace Smarthouse\Models\Admin;

use Smarthouse\Services\DBConnService;

class AdminPanelModel
{
    private $categoriesStats;
    private $todaysOrders;
    private $ordersTotal;
    private $ordersInWork;


    public function __construct()
    {
        $this->init();
    }

    public function getCategoriesStats(): array
    {
        return $this->categoriesStats;
    }

    public function getTodayOrders(): array
    {
        return $this->todaysOrders;
    }

    public function getOrdersTotal(): array
    {
        return $this->ordersTotal;
    }

    public function getOrdersInWork(): ?array
    {
        return $this->ordersInWork;
    }

    public function getOrdersInWorkAmount(): int
    {
        $res = 0;
        foreach ($this->ordersInWork as $order) {
            $res += $order['tot_amount'];
        }
        return $res;
    }

    public function getOrdersInWorkSumm(): float
    {
        $res = 0;
        foreach ($this->ordersInWork as $order) {
            $res += $order['tot_price'];
        }
        return $res;
    }

    public function getTotalGoods(): int
    {
        $res = 0;
        foreach ($this->categoriesStats as $item) {
            $res += $item['goods_count'];
        }
        return $res;
    }

    private function init()
    {
        $sql = "SELECT * FROM v_categories_statistic_all";
        $this->categoriesStats = DBConnService::selectRowsSet($sql, []);

        $sql = "    SELECT count(id) as amount,sum(tot_price) as summa,currency from v_orders
        WHERE date_open>=curdate()-2
        GROUP BY currency";
        $this->todaysOrders = DBConnService::selectRowsSet($sql, []);

        $sql = "SELECT * from v_orders ORDER BY date_open DESC";
        $this->ordersInWork = DBConnService::selectRowsSet($sql, []);
    }
}
