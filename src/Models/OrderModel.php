<?php

namespace Smarthouse\Models;

use Exception;
use Smarthouse\Services\DBConnService;

class OrderModel
{
    private $login;
    private $cartItems;
    private $deliveryType;
    private $paymentType;
    private $deliveryAdress;
    private $contactName;
    private $contactPhone;

    public function __construct()
    {
        $this->login = (isset($_SESSION['login'])) ? $_SESSION['login'] : null;
    }

    public function init(array $data): array
    {
        if (($this->login == null) && ($data['contactPhone'] == null)) {
            return ['error' => 'Cannot register order of unautorized user whithout contact phone'];
        }
        $this->cartItems = $data['cartItems'];
        $this->deliveryType = $data['deliveryType'];
        $this->paymentType = $data['paymentType'];
        $this->deliveryAdress = $data['deliveryAdress'];
        $this->contactName = $data['contactName'];
        $this->contactPhone = $data['contactPhone'];
        return [];
    }

    public function registerOrder(): array
    {
        $dBase = DBConnService::getConnection();
        try {
            $dBase->beginTransaction();
            $sql = 'CALL register_order_header(?,?,?,?,?,?)';
            $row = DBConnService::selectSingleRow(
                $sql,
                [
                    $this->login,
                    $this->deliveryType,
                    $this->paymentType,
                    $this->deliveryAdress,
                    $this->contactName,
                    $this->contactPhone
                ]
            );
            $id = $row['order_id'];
            $sql = 'INSERT INTO order_positions(order_id, good_id, amount, price, currency) VALUES (?,?,?,?,?)';
            //Устанавливаем позиции в заказе
            foreach ($this->cartItems as $item) {
                $res = DBConnService::execQuery($sql, [$id, $item['id'], $item['amount'], $item['price'], $item['currency']]);
            }
            //Чистим корзину
            if ($this->login != null) {
                $sql = "DELETE FROM cart WHERE user=?";
                $res = DBConnService::execQuery($sql, [$this->login]);
            }
            //Регистрируем первый статус заказа
            $sql="INSERT INTO orders_handle_history (order_id) VALUES (?)";
            $res = DBConnService::execQuery($sql, [$id]);

            $dBase->commit();
            return ['status' => 'success'];
        } catch (Exception $e) {
            $dBase->rollBack();
            return ['error' => $e->getMessage()];
        }
    }
}
