<?php

namespace Smarthouse\Services;

use PDO;

define('DB_DRIVER', 'mysql');
define('DB_HOST', '195.133.1.84');
define('DB_NAME', 'smarthouse');
define('DB_USER', 'smarthouse_guest');
define('DB_PASS', 'I_am_a_guest_no_1');


define('GOODS_LIM', 12);

final class DBConnService
{
    private static $dBase;
    private function __construct()
    {
    }
    public static function getConnection(): PDO
    {
        if (self::$dBase == null) {
            $connect_str = DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME;
            self::$dBase = new PDO($connect_str, DB_USER, DB_PASS);
            self::$dBase->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        };
        return self::$dBase;
    }
}
