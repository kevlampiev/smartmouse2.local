<?php


namespace Smarthouse\Models;

use Smarthouse\Services\DBConnService;
use PDO;

class User
{
    private $login;
    private $pass;
    private $name;
    private $phone;
    private $email;
    private $adress;
    private $description;
    private $dbConnection;
    private $isLogged;


    public function __construct()
    {
        $this->dbConnection = DBConnService::getConnection();
        $this->isLogged = $this->init();
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPass(): string
    {
        return $this->pass;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAdress(): string
    {
        return $this->adress;
    }

    public function getDescription(): string
    {
        return $this->description;
    }


    /**
     * Вспомогательные функции 
     */
    private function fillData(array $userInfo): void
    {
        $this->login = $userInfo['login'];
        $this->pass = $userInfo['pass'];
        $this->name = $userInfo['name'];
        $this->phone = $userInfo['phone'];
        $this->email = $userInfo['email'];
        $this->adress = $userInfo['address'];
        $this->description = $userInfo['description'];
        $this->goodsAmount = $userInfo['goods_amount'];
        $this->cartTotal = $userInfo['goods_total'];
    }

    private function flushData(): void
    {
        $this->login = null;
        $this->pass = null;
        $this->name = "guest";
        $this->phone = null;
        $this->email = null;
        $this->adress = null;
        $this->description = null;
        $this->goodsAmount = null;
        $this->cartTotal = null;
    }

    /**
     * Практически автологин
     */
    public function init()
    {
        if ($this->isLogged) {
            return true; //все уже и так хорошо, нечего менять 
        }

        if (isset($_SESSION['login'])) {
            //хороший случай, все уже в системе
            $sql = "SELECT * FROM v_uer_cart_stats WHERE login=?";
            $data = ['login' => $_SESSION['login']];
            $stmt = $this->dbConnection->prepare($sql);
            $stmt->execute($data);
            $this->fillData($stmt->fetchAll(PDO::FETCH_ASSOC)[0]);
            $this->isLogged = true;
            return true;
        }
        return false;
    }

    public function logIn(string $login, string $password, ?string $rememberMe): ?array
    {
        $sql = "SELECT * FROM v_uer_cart_stats WHERE login=?";
        $stmt = $this->dbConnection->prepare($sql);

        $stmt->execute(['login' => $login]);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

        if ($row == []) {
            $this->isLogged = false;
            return ["error" => "user with login $login doesn't exist "];
        }

        if (!password_verify($password, $row['password'])) {
            //пароль не совпадает
            $this->isLogged = false;
            return ["error" => "password is incorrect "];
        }

        $this->fillData($row);
        return $row[0];
    }

    public function logout(): void
    {
        $this->isLogged = false;
        $this->flushData();
    }
}
