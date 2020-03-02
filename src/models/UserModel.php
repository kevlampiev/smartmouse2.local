<?php


namespace Smarthouse\Models;

use Smarthouse\Services\DBConnService;
use PDO;

class UserModel
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
        session_start();
        $this->dbConnection = DBConnService::getConnection();
        $this->isLogged = $this->init();
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getPass(): ?string
    {
        return $this->pass;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function getDescription(): ?string
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
        $this->login = "guest";
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
            setcookie("is_logged_in", "true"); //наследие царского режима, надо убрать
            return true; //все уже и так хорошо, нечего менять 
        }

        if ($_SESSION['login'] != null) {
            //хороший случай, все уже в системе
            $sql = "SELECT * FROM v_usr_cart_stats WHERE login=?";
            $data = [$_SESSION['login']];
            $stmt = $this->dbConnection->prepare($sql);
            $stmt->execute($data);
            $this->fillData($stmt->fetchAll(PDO::FETCH_ASSOC)[0]);
            $this->isLogged = true;
            setcookie("is_logged_in", "true"); //наследие царского режима, надо убрать
            return true;
        }
        $this->flushData();
        return false;
    }

    public function logIn(string $login, string $password, ?string $rememberMe): ?array
    {
        $sql = "SELECT * FROM v_usr_cart_stats WHERE login=?";
        $stmt = $this->dbConnection->prepare($sql);

        $stmt->execute([$login]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $row = $rows[0];

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
        $_SESSION['login'] = $this->login;
        setcookie("is_logged_in", "true"); //наследие царского режима, надо убрать
        return $row;
    }

    public function logout(): void
    {
        $this->isLogged = false;
        unset($_SESSION['login']);
        setcookie("is_logged_in", "false"); //наследие царского режима, надо убрать
        $this->flushData();
    }
}
