<?php

namespace Smarthouse\Services;

class RequestService
{
    private function construct()
    {
    }

    static public function getPostStr(string $param): ?string
    {
        if (!isset($_POST[$param])) {
            return null;
        };

        return trim(strip_tags((string) $_POST[$param]));
    }
}
