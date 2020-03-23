<?php


namespace Smarthouse\Models;

use Smarthouse\Services\DBConnService;
use PDO;

//Класс-наследник 

class AdminModel extends UserModel
{

    private $logInError;

    public function __construct()
    {
        parent::__construct();
        $this->isLogged = $this->alreadyLoged();
    }

    public function getLogError(): string
    {
        return ($this->logInError != null ? $this->logInError : '');
    }

    //нужно больше условий на проверку
    protected function alreadyLoged(): bool
    {
        return (parent::alreadyLoged()
            && isset($_SESSION['admin_mode'])
            && ($_SESSION['admin_mode'] == "admin"));
    }

    /**
     * Практически автологин
     */
    protected function init()
    {
        setcookie("is_logged_in", "false", time() - 7 * 24 * 3600); //иначе почему то не затирает куки на некоторых страницах и vue.js срабатывает плохо   

        if ($this->alreadyLoged()) {
            //хороший случай, все уже в системе
            try {
                $sql = "SELECT * FROM users WHERE login=?";
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


    protected function grantAccess(): void
    {
        $this->isLogged = true;
        //куки не нужны. Если и были - удаляем
        setcookie("is_logged_in", "false", time() - 7 * 24 * 3600);
        $_SESSION['login'] = $this->getLogin();
        $_SESSION['admin_mode'] = 'admin';
        $this->logInError = '';
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

        $sql = "SELECT * FROM users WHERE login=?";
        $row = DBConnService::selectSingleRow($sql, [$login]);

        if ($row == []) {
            $this->isLogged = false;
            $this->logInError = "user with login $login doesn't exist ";
            return ["error" => $this->logInError];
        }

        if ($row['user_role'] != 'admin') {
            //это не администратор
            $this->isLogged = false;
            $this->logInError = "user $login doesn't have administration privilegies";
            return ["error" => $this->logInError];
        }

        if (!password_verify($password, $row['password'])) {
            //пароль не совпадает
            $this->isLogged = false;
            $this->logInError = "password is incorrect ";
            return ["error" => $this->logInError];
        }

        $sql = "UPDATE users SET last_login=CURRENT_TIMESTAMP() WHERE login=?";
        DBConnService::execQuery($sql, [$login]);
        $this->fillData($row);
        $this->grantAccess();

        return $row;
    }
}
