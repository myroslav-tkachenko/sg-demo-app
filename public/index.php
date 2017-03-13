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

$routes->add('get_login', new Route('/login', array('_controller' => function ($request, $response) {
    return $response->setContent('<form action="/login" method="POST">
        <input name="name">
        <input name="pass">
        <input type="submit">
    </form>');
}), array(), array(), '', array(), array('GET')));

$routes->add('post_login', new Route('/login', array('_controller' => function ($request) {
    $login = $request->request->get('name');
    $pass = $request->request->get('pass');

    $session = $request->getSession();
    if ($login == 'test' && $pass == '123') {
        $session->set('logged', true);
        return new RedirectResponse('/cabinet');
    }

    return new RedirectResponse('/login');
}), array(), array(), '', array(), array('POST')));

$routes->add('logout', new Route('/logout', array('_controller' => function ($request, $response) {
    $session = $request->getSession();
    $session->invalidate();

    return new RedirectResponse('/');
}), array(), array(), '', array(), array('GET')));

$routes->add('cabinet', new Route('/cabinet', array('_controller' => function ($request, $response) {
    // Check if user was set in Session
    $logged = $request->getSession()->get('logged');
    if ($logged) {
        $response->setContent('CABINET');
    } else {
        $response->setStatusCode('403');
        $response->setContent('Forbidden.');
    }

    return $response;
}), array(), array(), '', array(), array('GET')));

$routes->add('front_route', new Route('/', array('_controller' => function ($request, $response) {

    $db = new PDO("mysql:host=localhost;dbname=rss_news;charset=utf8", "root", "123");
    $sql = "SELECT * FROM news ORDER BY id DESC LIMIT 50";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response->setContent(include '../templates/index.tpl.php');
    return $response;
})));

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


if (isset($action) && is_callable($action)) {
    $response = $action($request, $response);
} else {
    $response->setStatusCode('404');
    $response->setContent('404: Page not found');
}

$response->send();
