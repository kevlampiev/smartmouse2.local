<?php

namespace Smarthouse\Models;

use Smarthouse\Services\DBConnService;

class CartModel
{

    protected $login;
    protected $goodsSet;

    public function __construct()
    {
        session_start();
        $this->login = isset($_SESSION['login']) ? $_SESSION['login'] : null;
        $this->goodsSet = $this->getCart();
    }

    public function getCart(): array
    {

        if ($this->login == null) {
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
        return DBConnService::selectRowsSet($sql, [$this->login]);
    }

    public function addToCart(array $item): array
    {
        if ($this->login == null) {
            return ['error' => 'user is not defined'];
        }
        //проверка а есть ли такой товар
        $sql = "SELECT * FROM goods WHERE id=?";
        $res = DBConnService::selectRowsSet($sql, [$item['id']]);
        if (count($res) === 0) {
            return ['error' => "A good with id={$item['id']} doesn't exist"];
        }
        //Убедились, что товар есть, можно добавлять
        $sql = 'CALL add_to_cart(?,?,?)';
        $result=DBConnService::execQuery(
            $sql,
            [$this->login, $item['id'], $item['amount']]
        );
        if ($result['status']=='Ok') {
            $this->goodsSet[]=$item;
        }
        return $result;
    }

    public function editCartItem(array $item): array
    {
        if ($this->goodsSet == null) {
            return ['error' => 'user is not defined'];
        }

        //проверка а есть ли такой товар
        $sql = "SELECT * FROM goods WHERE id=?";
        $res = DBConnService::selectRowsSet($sql, [$item['id']]);
        if ($res==[]) {
            return ['error' => "A good with id={$item['id']} doesn't exist"];
        }
        $sql = 'CALL edit_cart_item(?,?,?)';
        $response=DBConnService::execQuery(
            $sql,
            [$_SESSION['login'], $item['id'], $item['amount']]
        );
        if ($response['status']=="Ok") {
            foreach($this->goodsSet as $good) {
                if ($good['id']==$item['id']) {
                    $found=true;
                    $good['amount']=$item['amount'];
                }
            }
            if (!$fount) {
                $this->goodsSet[]=$item;
            }
        }
        return $response;
    }

    public function mergeCarts(array $localCart): array
    {
        foreach ($localCart as $item) {
            $this->addToCart($item);
        }
        $this->goodsSet=$this->getCart();
        return $this->goodsSet;
    }

    public function handleCart(string $action, array $item=null):array {
        switch ($action) {
            case 'getCart':
                $result = $this->getCart();
                break;
            case 'saveCart':
                $result = [];
                break;
            case 'mergeCarts':
                $result = $this->mergeCarts($item);
                break;
            case 'addToCart':
                $result = $this->addToCart($item);
                break;
            case 'editCartItem':
                $result = $this->editCartItem($item);
                break;
            default:
                $result=['error' => "Operation '$action' is not defined ... "];
        }     
        return $result;   
    }


}
