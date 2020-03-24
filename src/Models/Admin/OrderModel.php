<?php

namespace Smarthouse\Models\Admin;

use DateTime;
use Smarthouse\Services\DBConnService;

class OrderModel
{

    private $id;
    private $dateOpen;
    private $login;
    private $delivery;
    private $payment;
    private $contactName;
    private $contactPhone;
    private $comment;
    private $deliveryAdress;
    private $totAmount;
    private $totPrice;
    private $currency;
    private $fieldMatches; //не сделано а жаль

    private $goodsList;
    private $handleHistory;

    public function __construct(int $id)
    {
        $sql = 'SELECT * FROM v_orders WHERE id=?';
        $res = DBConnService::selectSingleRow($sql, [$id]);
        if ($res == []) {
            $this->id = null;
        } else {
            $this->init($res);
        }
    }

    private function init(array $data): void
    {
        $this->id = $data['id'];
        $this->dateOpen = $data['date_open'];
        $this->login = $data['login'];
        $this->delivery = $data['delivery'];
        $this->payment = $data['payment'];
        $this->contactName = $data['contact_name'];
        $this->contactPhone = $data['contact_phone'];
        $this->comment = $data['comment'];
        $this->deliveryAdress = $data['delivery_address'];
        $this->totAmount = $data['tot_amount'];
        $this->totPrice = $data['tot_price'];
        $this->currency = $data['currency'];

        $sql = "SELECT * from v_order_positions WHERE order_id=?";
        $this->goodsList = DBConnService::selectRowsSet($sql, [$this->id]);

        $sql = "SELECT * FROM v_orders_handle_history WHERE order_id=? ORDER BY date_open DESC";
        $this->handleHistory = DBConnService::selectRowsSet($sql, [$this->id]);
    }

    public function getId(): int
    {
        return (int) $this->id;
    }
    public function getdateOpen(): string
    {
        return $this->dateOpen;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getDelivery(): string
    {
        return $this->delivery;
    }

    public function getPayment(): string
    {
        return $this->payment;
    }

    public function getContactName(): ?string
    {
        return $this->contactName;
    }
    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getDeliveryAdress(): ?string
    {
        return $this->deliveryAdress;
    }

    public function getGoodsList(): array
    {
        return $this->goodsList;
    }

    public function getHandleHistory(): array
    {
        return $this->handleHistory;
    }


    public function getTotAmount(): int
    {
        return (int) $this->totAmount;
    }

    public function getTotPrice(): float
    {
        return (float) $this->totPrice;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getDeliveriesList(): array
    {
        $sql = "SELECT id,name FROM delivery_types ORDER BY name";
        return DBConnService::selectRowsSet($sql);
    }

    public function getPaymentsList(): array
    {
        $sql = "SELECT id,name FROM payment_types ORDER BY name";
        return DBConnService::selectRowsSet($sql);
    }

    public function setNextOrderStatus(?string $comment): array
    {
        $sql = "CALL order_next_step(?,?)";
        $result = DBConnService::selectSingleRow($sql, [$this->id, $comment]);
        return $result;
    }

    public function cancelOrder(?string $comment): array
    {
        $sql = "CALL cancel_order(?,?)";
        $result = DBConnService::selectSingleRow($sql, [$this->id, $comment]);
        return $result;
    }

    public function editOrder(array $params): array
    {
        $sql = "CALL edit_order(?,?,?,?,?,?,?)";
        $result = DBConnService::selectSingleRow(
            $sql,
            [
                $this->id,
                $params['delivery'],
                $params['payment'],
                $params['contactName'],
                $params['contactPhone'],
                $params['deliveryAdress'],
                $params['comments']
            ]
        );
        return $result;
    }

    public function handleOrder(array $params): array
    {
        switch ($params['action']) {
            case 'nextstep':
                $res = $this->setNextOrderStatus($params['comment']);
                break;
            case 'cancelOrder':
                $res = $this->cancelOrder($params['comment']);
                break;
            case 'editOrder':
                $res = $this->editOrder($params);
                break;
            default:
                $res = ['status' => "Error: unknown action {$params['action']}"];
        }
        return $res;
    }
}
