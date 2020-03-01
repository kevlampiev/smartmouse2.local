<?php

use Smarthouse\Controllers\ProductController;
use Smarthouse\Controllers\CategoryController;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Routing\AnnotatedRouteControllerLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;

use Composer\Autoload\ClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/** @var ClassLoader $loader */
$loader = require __DIR__ . '/../vendor/autoload.php';



$loader = new AnnotationDirectoryLoader(
    new FileLocator(__DIR__ . '/../src/Controllers/'),
    new AnnotatedRouteControllerLoader(
        new AnnotationReader()
    )
);

$routes = $loader->load(__DIR__ . '/../src/Controllers/');

// $routes = new RouteCollection();
// $routes->add('good', new Route('/good/{id}', array('_controller' => ProductController::class)));
// $routes->add('categories', new Route('/categories', array('_controller' => CategoryController::class)));

$context = new RequestContext('/');

$matcher = new UrlMatcher($routes, $context);

$parameters = $matcher->match('/' . $_GET['route']);
// print_r($parameters);

$controllerClass = $parameters['_controller'];
$controller = new $controllerClass();

$responce = $controller($parameters);
echo $responce;
//print_r($parameters);
// массив ('_controller' => 'MyController', '_route' => 'route_name')
