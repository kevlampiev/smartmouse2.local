<?php 

namespace Smarthouse\Tests\Models;
use PHPUnit\Framework\TestCase;
use Smarthouse\Models\Admin\OrderModel;
use Smarthouse\Services\DBConnService;

class OrderModelTest extends TestCase {
    public function setUp()    {
        $dbConn=DBConnService::getConnection(DB_DRIVER, DB_HOST,'smarthousetests');
        $sql="INSERT INTO orders()";
        
    }
}