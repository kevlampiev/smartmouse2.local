<?php

namespace Smarthouse\Models;

use Smarthouse\Services\DBConnService;

class CartModel {

    protected $login;
    protected $goodsSet;
    
    public function __construct(?string $login=null) {
        if ($login==null)&&(isset($_SESSION['login'])) {
            $login=$_SESSION['login'];
        }
        $this->login=$login;
        
        $this->goodsSet=$this->getCard();
    }

    public function getCart(): array {

        if ($this->login==null) {
            return [];
        }

        $sql = "SELECT good_id as id,
                    name,
                    img,
                    price,
                    currency,
                    amount
                FROM v_cart
                WHERE user=?
                ORDER BY name";
        return DBConnService::selectRows($sql, [$this->login]);
    }

    public function addToCart(array $item): array {
        
        if ($this->login==null) {
            return ['error' => 'user is not defined'];
        }

        //проверка а есть ли такой товар
        $sql = "SELECT * FROM goods WHERE id=?";
        $res = DBConnService::selectRows($sql, [$item['id']]);
        if (count($res) === 0) {
            return ['error' => "A good with id={$item['id']} doesn't exist"];
        }
        //Убедились, что товар есть, можно добавлять
        $sql = 'CALL add_to_cart(?,?,?)';
        return DBConnService::execQuery(
            $sql,
            [$this->login, $item['id'], $item['amount']]
        );
    }

    function editCartItem(array $item): array
    {
        if ($this->goodsList==null) {
            return ['error' => 'user is not defined'];
        }

        //проверка а есть ли такой товар
        $sql = "SELECT * FROM goods WHERE id=?";
        $res = selectRows($sql, [$item['id']]);
        if (count($res) === 0) {
            return ['error' => "A good with id={$item['id']} doesn't exist"];
        }
        $sql = 'CALL edit_cart_item(?,?,?)';
        insDelUpdRows(
            $sql,
            [$_SESSION['login'], $item['id'], $item['amount']]
        );
        return ['status' => 'All went fine ... probably...'];
    }

function mergeCarts(array $localCart): array
{
    foreach ($localCart as $item) {
        addToCart($item);
    }
    return getCart();
}


}

?>