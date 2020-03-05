<?php


namespace Smarthouse\Models;

use Smarthouse\Services\DBConnService;
use PDO;

//Класс-наследник, который может, крооме всего прочего авторизоваться из cookie по токену

class AdminModel extends UserModel
{


    public function __construct()
    {
        parent::__construct();
    }
}
