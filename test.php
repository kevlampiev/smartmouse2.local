<?php

use Smarthouse\Controllers\ProductController;
use Smarthouse\Test;

require_once __DIR__ . "/vendor/autoload.php";

$test = new Test();
$test->print();

$cont = new ProductController();
$cont->print();
