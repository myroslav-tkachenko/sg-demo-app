<?php

require_once '../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

// $_SERVER['REQUEST_URI'] = preg_replace('|/$|', '', $_SERVER['REQUEST_URI'], 1);
$request = Request::createFromGlobals();
$session = new Session();
$session->start();

$request->setSession($session);

$routes = new RouteCollection();

$routes->add('get_login', new Route('/login', array('_controller' => function ($request) {
    echo '<form action="/login" method="POST">
        <input name="name">
        <input name="pass">
        <input type="submit">
    </form>';
}), array(), array(), '', array(), array('GET')));

$routes->add('post_login', new Route('/login', array('_controller' => function ($request) {
    $login = $request->request->get('name');
    $pass = $request->request->get('pass');

    $session = $request->getSession();
    if ($login == 'test' && $pass == '123') {
        $session->set('logged', true);
    }
}), array(), array(), '', array(), array('POST')));

$routes->add('logout', new Route('/logout', array('_controller' => function ($request) {

    $session = $request->getSession();
    $session->invalidate();
    echo 'LOGOUT';
}), array(), array(), '', array(), array('GET')));

$routes->add('cabinet', new Route('/cabinet', array('_controller' => function ($request) {

    // Check if user was set in Session
    $logged = $request->getSession()->get('logged');
    if ($logged) {
        echo 'CABINET';
    } else {
        echo '403';
    }
}), array(), array(), '', array(), array('GET')));

$routes->add('front_route', new Route('/', array('_controller' => function () {

    $db = new PDO("mysql:host=localhost;dbname=rss_news;charset=utf8", "root", "123");
    $sql = "SELECT * FROM news ORDER BY id DESC LIMIT 50";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    include '../templates/index.tpl.php';
})));

$context = new RequestContext();
$context->fromRequest($request);

$matcher = new UrlMatcher($routes, $context);

try {
    $parameters = $matcher->matchRequest($request);
} catch (Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
    echo '404';
    die();
}

$action = $parameters['_controller'];

if (is_callable($action)) {
    $action($request);
}
