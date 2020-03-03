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
     * Вспомогательные функции - утилиты
     */
    protected function fillData(array $userInfo): void
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

    protected function flushData(): void
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


    protected function grantAccess(): void
    {
        $this->isLogged = true;
        setcookie("is_logged_in", "true");
        $_SESSION['login'] = $this->login;
    }

    protected function denyAccess(): void
    {
        unset($_SESSION['login']);
        setcookie("is_logged_in", "false", time() - 7 * 24 * 3600);
    }


    /**
     * Практически автологин
     */
    protected function init()
    {
        if ($this->isLogged) {
            $this->grantAccess();
            return true; //все уже и так хорошо, нечего менять 
        }

        if ($_SESSION['login'] != null) {
            //хороший случай, все уже в системе
            $sql = "SELECT * FROM v_usr_cart_stats WHERE login=?";
            $data = [$_SESSION['login']];
            $row = DBConnService::selectSingleRow($sql, $data);
            $this->fillData($row);


            $this->grantAccess();
            return true;
        }
        $this->flushData();
        return false;
    }

    public function logIn(string $login, string $password, ?string $rememberMe): ?array
    {

        $sql = "SELECT * FROM v_usr_cart_stats WHERE login=?";
        $row = DBConnService::selectSingleRow($sql, [$login]);

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
        $this->grantAccess();

        return $row;
    }

    public function logout(): void
    {
        $this->isLogged = false;
        $this->denyAccess();
        $this->flushData();
    }
}
