<?php

namespace Smarthouse\Models\Admin;

use Smarthouse\Services\DBConnService;

class OrdersListModel
{

    private $ordersList;

    public function __construct()
    {
        $sql = 'SELECT * FROM v_orders_with_stats ORDER BY date_open DESC';
        $this->ordersList = DBConnService::selectRowsSet($sql);
    }

    public function getOrdersList(): array
    {
        return $this->ordersList;
    }
}
