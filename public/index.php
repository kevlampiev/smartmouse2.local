<?php


use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Routing\AnnotatedRouteControllerLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;

use Composer\Autoload\ClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;



/** @var ClassLoader $loader */
$loader = require __DIR__ . '/../vendor/autoload.php';

AnnotationRegistry::registerLoader([$loader, 'loadClass']);


$loader = new AnnotationDirectoryLoader(
    new FileLocator(__DIR__ . '/../src/Controllers/'),
    new AnnotatedRouteControllerLoader(
        new AnnotationReader()
    )
);

$routes = $loader->load(__DIR__ . '/../src/Controllers/');

$context = new RequestContext('/');

$matcher = new UrlMatcher($routes, $context);

$parameters = $matcher->match('/' . $_GET['route']); //иначе отказывалась работать с Apache 
// print_r($parameters);

$controllerClass = $parameters['_controller'];
$controller = new $controllerClass();

$responce = $controller($parameters);
echo $responce;
