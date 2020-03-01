<?php

namespace Smarthouse\Services;

use \Twig\Loader\FilesystemLoader;
use  \Twig\Environment;


final class TwigService
{
    private static $twig;
    private function __construct()
    {
    }
    public static function getTwig(): Environment
    {
        if (self::$twig !== null) {
            return self::$twig;
        };

        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        self::$twig = new Environment($loader);
        return self::$twig;
    }
}
