<?php

namespace Smarthouse\Controllers\Admin;

use Smarthouse\Models\CategoriesModel;
use Smarthouse\Models\UserModel;
use Smarthouse\Services\DBConnService;

class BaseAdminController
{
    protected $params;

    protected function baseViewData(): array
    {
        return [
            'userInfo' => new UserModel()
        ];
    }
}
