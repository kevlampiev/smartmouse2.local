<?php

namespace Smarthouse\Controllers;

use Smarthouse\Services\TwigService;
use Smarthouse\Services\DBConnService;
use Smarthouse\Models\UserModel;
use Symfony\Component\Routing\Annotation\Route;

class BaseController
{
    private function formSliderData(): void
    {
        $result = DBConnService::selectRowsSet("SELECT * FROM slider_info", []);
        setcookie('slides', json_encode($result));
    }

    private function hotOffers(): array
    {
        return DBConnService::selectRowsSet("SELECT * FROM v_hot_offer", []);
    }

    private function mostPopulars(): array
    {
        return DBConnService::selectRowsSet("SELECT * FROM v_hot_offer", []);
    }

    /**
     * @Route("/", name="base")
     */
    public function __invoke(): string
    {
        $this->formSliderData();

        $twig = TwigService::getTwig();
        $hotOffer = $twig->render('components/goodsListComp.twig', [
            'goodListTitle' => 'hot offers',
            'goods' => $this->hotOffers()
        ]);

        $mostPopular = $twig->render('components/goodsListComp.twig', [
            'goodListTitle' => 'most popular',
            'goods' => $this->mostPopulars()
        ]);

        // $content = $twig->render('main.twig', [
        //     'hotOffer' => $hotOffer,
        //     'mostPopular' => $mostPopular
        // ]);

        $user = new UserModel();
        return $twig->render(
            'layouts/mainLayout.twig',
            [
                'content' => "main.twig", 'userInfo' => $user,
                'hotOffer' => $hotOffer,
                'mostPopular' => $mostPopular
            ]
        );
    }
}
