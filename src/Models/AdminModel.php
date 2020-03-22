<?php


namespace Smarthouse\Models;

use Smarthouse\Services\DBConnService;
use PDO;

//Класс-наследник 

class AdminModel extends UserModel
{


    public function __construct()
    {
        parent::__construct();
    }

    //нужно больше условий на проверку
    protected function alreadyLoged(): bool
    {
        return (parent::alreadyLoged()
            && isset($_SESSION['admin_mode'])
            && ($_SESSION['admin_mode'] == "admin"));
    }


    protected function grantAccess(): void
    {
        $this->isLogged = true;
        //куки не нужны
        setcookie("is_logged_in", "false", time() - 7 * 24 * 3600);
        $_SESSION['login'] = $this->login;
        $_SESSION['admin_mode'] = 'admin';
    }

    protected function denyAccess(): void
    {
        unset($_SESSION['login']);
        unset($_SESSION['admin_mode']);
        setcookie("is_logged_in", "false", time() - 7 * 24 * 3600);
    }

    //переопределяем нахрен
    public function logIn(string $login, string $password, ?string $rememberMe): ?array
    {

        $sql = "SELECT * FROM v_usr_cart_stats WHERE login=?";
        $row = DBConnService::selectSingleRow($sql, [$login]);

        if ($row == []) {
            $this->isLogged = false;
            return ["error" => "user with login $login doesn't exist "];
        }

        if ($row['user_role'] != 'admin') {
            //это не администратор
            $this->isLogged = false;
            return ["error" => "user $login doesn't have administration privilegies"];
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
}
