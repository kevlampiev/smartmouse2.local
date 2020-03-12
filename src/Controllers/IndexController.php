<?php

namespace Smarthouse\Controllers;

use Smarthouse\Services\TwigService;
use Smarthouse\Services\DBConnService;
use Smarthouse\Models\CustomerModel;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends BaseCustController
{
    private function formSliderData(): void
    {
        $result = DBConnService::selectRowsSet("SELECT * FROM slider_info", []);
        setcookie('slides', json_encode($result));
    }

    private function getHotOfferContent(): array
    {
        $goods = DBConnService::selectRowsSet("SELECT * FROM v_hot_offer", []);
        return [
            'goodListTitle' => 'hot offers',
            'goods' => $goods
        ];
    }

    private function getMostPopularContent(): array
    {
        $goods = DBConnService::selectRowsSet("SELECT * FROM v_hot_offer", []);
        return  [
            'goodListTitle' => 'most popular',
            'goods' => $goods
        ];
    }

    /**
     * @Route("/", name="base")
     */
    public function __invoke(): string
    {
        $this->formSliderData();

        $twig = TwigService::getTwig();

        $user = new CustomerModel();
        return $twig->render(
            'main.twig',
            [
                'content' => "main.twig",
                'userInfo' => $user,
                'hotOffer' => $this->getHotOfferContent(),
                'mostPopular' => $this->getMostPopularContent(),
                'categories' => $this->getCategories()
            ]
        );
    }
}
