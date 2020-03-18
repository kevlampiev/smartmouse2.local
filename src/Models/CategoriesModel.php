<?php


namespace Smarthouse\Models;

use Smarthouse\Services\DBConnService;


class CategoriesModel
{
    private $categories;
    public function __construct()
    {
        $sql = "SELECT * from v_categories_statistics";
        $this->categories = DBConnService::selectRowsSet($sql);
    }

    public function getCategories(): array
    {
        return $this->categories;
    }
}
