<?php

namespace Smarthouse\Controllers\Customer;

use Smarthouse\Models\SingleGoodModel;
use Smarthouse\Models\CustomerModel;
use Smarthouse\Services\TwigService;
use Symfony\Component\Routing\Annotation\Route;

class ContactsController extends BaseCustController
{
    /**
     * @Route("/contacts", name="contacts")
     */
    public function __invoke(): string
    {   $twig = TwigService::getTwig();
            return $twig->render('contacts.twig');
    }
}
