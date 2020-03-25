<?php


namespace Smarthouse\Models;

use Smarthouse\Services\DBConnService;
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

    protected function fillData(array $userInfo): void {
        parent::fillData($userInfo);
        $this->goodsAmount = $userInfo['goods_amount'];
        $this->cartTotal = $userInfo['goods_total'];        
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

    public function getOrdersList(): array
    {
        $sql = 'SELECT * from v_orders WHERE login=?';
        $lo = $this->getLogin();
        return DBConnService::selectRowsSet($sql, [$this->getLogin()]);
    }
}
