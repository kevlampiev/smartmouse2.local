<?php

namespace Smarthouse\Controllers;

use Smarthouse\Models\SingleGoodModel;
use Smarthouse\Models\CustomerModel;
use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

class SingleGoodController extends BaseCustController
{
    /**
     * @Route("/good/{id}", name="good")
     */
    public function __invoke(array $parameters): string
    {
        $good = new SingleGoodModel($parameters['id']);


        $twig = TwigService::getTwig();
        if ($good->getName() != null) {
            $user = new CustomerModel();
            return $twig->render('single_good.twig', ['good' => $good, 'userInfo' => $user]);
        } else {
            return $twig->render('layouts/not_found.twig');;
        }
    }
}
