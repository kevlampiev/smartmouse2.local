<?php

namespace Smarthouse\Services;

use Smarthouse\Services\DBConnService;
use PDO;

class TokenService
{
    private function __construct()
    {
    }
    public static function newTokenUnit(): string
    {
        return random_bytes(64);
    }

    public static function newToken(): array
    {
        return [
            'token_seria' => self::newTokenUnit(),
            'token_number' => self::newTokenUnit()
        ];
    }

    public static function registerNewToken(string $login): void
    {
        $token = self::newToken();
        self::writeUserToken($token);
        $sql = "INSERT INTO user_tokens($login,token_seria,token_number) VALUES(?,?,?)";
        DBConnService::execQuery($sql, [$login, $token['token_seria'], $token['token_number']]);
    }
    /**
     * Читает token из cookie
     */
    public static function readUserToken(): array
    {
        if (isset($_COOKIE['token'])) {
            $tokenStr = base64_decode($_COOKIE['token']);
            $arr = explode(":", $tokenStr);
            return ["token_seria" => $arr[0], "token_number" => $arr[1]];
        }
        return [];
    }

    /**
     * Записывает токен в cookie 
     */
    public static function writeUserToken(array $token): void
    {
        setcookie(
            "token",
            base64_encode(implode(':', [$token['token_seria'], $token['token_number']])),
            time() + 3600 * 24 * 7
        );
    }

    public static function getTokenUser(array $token): array
    {
        $sql = "SELECT * FROM v_usr_tokens WHERE token_seria=? AND token_number=?";
        return DBConnService::selectSingleRow($sql, [$token['token_seria'], $token['token_number']]);
    }

    public static function changeTokenNumber(array $token): void
    {
        $newTokenNumber = self::newTokenUnit();
        $sql = 'UPDATE user_tokens SET token_number=? WHERE  token_seria=?';
        $result = DBConnService::execQuery($sql, [$newTokenNumber, $token['token_seria']]);
        $token['token_number'] = $newTokenNumber;
        self::writeUserToken($token);
    }

    public static function destroyToken(): void
    {
        $token = self::readUserToken();
        setcookie(
            "token",
            base64_encode(implode(':', ["", ""])),
            time() - 1
        );
        $sql = "DELETE FROM user_tokens WHERE token_seria=?";
        $result = DBConnService::execQuery($sql, [$token['token_seria']]);
    }
}
