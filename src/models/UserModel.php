<?php


namespace Smarthouse\Models;

use Exception;
use Smarthouse\Services\DBConnService;
use Smarthouse\Services\RequestService;

class UserModel
{
    private $login;
    private $pass;
    private $pass2;
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
        $this->pass = $userInfo['password'];
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
        setcookie("is_logged_in", "false", time() - 7 * 24 * 3600); //иначе почему то не затирает куки на некоторых страницах и vue.js срабатывает плохо
        if ($this->isLogged) {
            $this->grantAccess();
            return true; //все уже и так хорошо, нечего менять 
        }


        if ($_SESSION['login'] != null) {
            //хороший случай, все уже в системе
            try {
                $sql = "SELECT * FROM v_usr_cart_stats WHERE login=?";
                $data = [$_SESSION['login']];
                $row = DBConnService::selectSingleRow($sql, $data);
                if ($row == null || $row == []) {
                    $this->denyAccess();
                    return false;
                } else {
                    $this->fillData($row);
                    $this->grantAccess();
                }
            } catch (Exception $e) {
                $this->denyAccess();
                return false;
            }

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

        $sql = "UPDATE users SET last_login=CURRENT_TIMESTAMP() WHERE login=?";
        DBConnService::execQuery($sql, [$login]);

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



    //Читает данные из get-запроса о новом потенциальном юзере
    public function readPostRequest(): void
    {
        //Task Оптимизировать.Какое-то безобразие 
        $this->login = RequestService::getPostStr('login');
        $this->pass = RequestService::getPostStr('password');
        $this->pass2 = RequestService::getPostStr('repass');
        $this->name = RequestService::getPostStr('name');
        $this->phone = RequestService::getPostStr('phone');
        $this->email = RequestService::getPostStr('email');
        $this->adress = RequestService::getPostStr('address');
        $this->description = RequestService::getPostStr('comment');
    }

    public function dataUserErrors(): string
    {
        $result = "";
        if (empty($this->login)) $result = "- user login must be set <br>";
        //   if (empty($this->pass)) $result = "- password must be set <br>";
        if (($this->pass) !== ($this->pass2)) $result .= "- the entered passwords do not match <br>";
        if (empty($this->name)) $result .= "- field \"name\" must be filled out <br>";
        if (empty($this->email)) $result .= "- field \"email\" must be filled out <br>";

        $sql = "SELECT login FROM users WHERE login=?";
        $n = count(DBConnService::selectRowsSet($sql, [$this->login]));
        if ($n > 0) $result .= "user with login {$this->login} already exists <br>";


        return $result;
    }

    public function registerNewUser(): void
    {
        $sql = "INSERT INTO users (login, password,  name, phone, email, address, description) 
        VALUES (?,?,?,?,?,?,?)";
        $this->pass = password_hash($this->pass, PASSWORD_DEFAULT);
        $params = array(
            $this->login,
            $this->pass,
            $this->name,
            $this->phone,
            $this->email,
            $this->address,
            $this->description
        );
        DBConnService::execQuery($sql, $params);

        $this->grantAccess();
    }

    //Task. Как то надо сделать, чтобы напрямую нелдьзя было пароль передать 
    public function updateUserField(string $fieldName, string $fieldValue): array
    {
        if (!$this->isLogged) {
            return ["error" => "user is not autorized"];
        }
        $sql = "UPDATE users SET " . "$fieldName" . "=? WHERE login=?";
        try {
            DBConnService::execQuery($sql, [$fieldValue, $this->login]);
            return ["status" => "success"];
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function updatePassword(string $currentPassword, string $newPassword): array
    {
        if (!password_verify($currentPassword, $this->pass)) {
            return ($this->isLogged) ?
                ['error' => 'user is not autorised'] :
                ['error' => 'current password is invalid'];
        }
        $this->pass = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->updateUserField("password", $this->pass);
    }
}
