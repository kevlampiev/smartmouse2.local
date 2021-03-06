<?php

namespace Smarthouse\Controllers\Customer;

use Smarthouse\Models\CategoriesModel;
use Smarthouse\Models\CustomerModel;

class BaseCustController
{
    protected $params;

    protected function baseViewData(): array
    {
        return [
            'userInfo' => new CustomerModel()
        ];
    }

    protected function getCategories()
    {
        $categories = new CategoriesModel();
        return $categories->getCategories();
    }
}
