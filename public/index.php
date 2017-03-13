<?php

require_once '../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

// $_SERVER['REQUEST_URI'] = preg_replace('|/$|', '', $_SERVER['REQUEST_URI'], 1);
$request = Request::createFromGlobals();
$response = new Response;

$session = new Session;
$session->start();

$request->setSession($session);

$routes = new RouteCollection();

$routes->add('get_login', new Route('/login', ['_controller' => 'App\Controller\Front@getLogin'],
    [], [], '', [], ['GET']));
$routes->add('post_login', new Route('/login', ['_controller' => 'App\Controller\Front@postLogin'],
    [], [], '', [], ['POST']));
$routes->add('logout', new Route('/logout', ['_controller' => 'App\Controller\Front@getLogout'],
    [], [], '', [], ['GET']));
$routes->add('cabinet', new Route('/cabinet', ['_controller' => 'App\Controller\Front@getCabinet'],
    [], [], '', [], ['GET']));
$routes->add('index', new Route('/', ['_controller' => 'App\Controller\Front@getIndex'],
    [], [], '', [], ['GET']));

$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);

try {
    $parameters = $matcher->matchRequest($request);
    $action = $parameters['_controller'];
} catch (Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
    $response->setStatusCode('404');
    $response->setContent('404: Page not found');
}

if (isset($action) && is_string($action)) {
    $controller = explode('@', $action);

    $controller_class_name = $controller[0];
    $class = new $controller_class_name;
    $method = $controller[1];

    $response = $class->$method($request, $response);
}

if (isset($action) && is_callable($action)) {
    $response = $action($request, $response);
}

if (! isset($action)) {
    $response->setStatusCode('404');
    $response->setContent('404: Page not found');
}

$response->send();
