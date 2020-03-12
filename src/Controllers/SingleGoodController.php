<?php

namespace Smarthouse\Controllers;

use Smarthouse\Models\SingleGoodModel;
use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

class SingleGoodController
{
    /**
     * @Route("/good/{id}", name="good")
     */
    public function __invoke(array $parameters): string
    {
        $good = new SingleGoodModel($parameters['id']);

        $twig = TwigService::getTwig();
        if ($good->getName() != null) {
            return $twig->render('single_good.twig', ['good' => $good]);
        } else {
            return "<p> 404 </p>";
        }
    }
}
