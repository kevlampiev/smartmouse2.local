<?php


namespace Smarthouse\Models;

use Smarthouse\Services\TokenService;

//Класс-наследник, который может, кроме всего прочего авторизоваться из cookie по токену

class CustomerModel extends UserModel
{

    public function __construct()
    {
        parent::__construct();
    }


    public function init()
    {
        if (parent::init()) {
            return true;
        }
        //Два случая авторизации не прошло. Опознанание по токену 
        $token = TokenService::readUserToken();
        if ($token !== []) {
            $row = TokenService::getTokenUser($token);
            if ($row == []) {
                return false;
            }
            TokenService::changeTokenNumber($token);

            $this->fillData($row);
            $this->grantAccess();
            return true;
        }
        $this->flushData();
        return false;
    }


    public function logIn(string $login, string $password, ?string $rememberMe): ?array
    {
        $row = parent::logIn($login, $password, $rememberMe);
        if (!array_key_exists('error', $row) && isset($rememberMe)) {
            TokenService::registerNewToken($login);
        }
        return $row;
    }


    public function logout(): void
    {
        TokenService::destroyToken();
        parent::logout();
    }
}
