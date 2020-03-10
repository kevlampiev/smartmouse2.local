<?php

namespace Smarthouse\Controllers;

use Smarthouse\Models\CategoriesModel;
use Smarthouse\Services\DBConnService;

class BaseCustController
{
    protected function getCategories()
    {
        $categories = new CategoriesModel();
        return $categories->getCategories();
    }
}
