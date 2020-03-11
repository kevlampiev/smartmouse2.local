<?php

namespace Smarthouse\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Smarthouse\Models\CategoriesModel;

class CategoryExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [new TwigFunction('categoriesData', [$this, 'categoriesData'])];
    }

    public function categoriesData(?int $currentCategory = null): array
    {
        $categories = new CategoriesModel();
        return [
            'categories' => $categories->getCategories(),
            'selected_id' => $currentCategory
        ];
    }
}
