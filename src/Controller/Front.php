<?php

namespace App\Controller;

use \PDO;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Front
{
    public function getIndex($request, $response)
    {
        $db = new PDO("mysql:host=localhost;dbname=rss_news;charset=utf8", "root", "123");
        $sql = "SELECT * FROM news ORDER BY id DESC LIMIT 50";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $response->setContent(include '../templates/index.tpl.php');
        return $response;
    }
    
    public function getLogin($request, $response)
    {
        return $response->setContent('<form action="/login" method="POST">
            <input name="name">
            <input name="pass">
            <input type="submit">
        </form>');
    }

    public function postLogin($request)
    {
        $login = $request->request->get('name');
        $pass = $request->request->get('pass');

        $session = $request->getSession();
        if ($login == 'test' && $pass == '123') {
            $session->set('logged', true);
            return new RedirectResponse('/cabinet');
        }

        return new RedirectResponse('/login');
    }

    public function getLogout($request, $response)
    {
        $session = $request->getSession();
        $session->invalidate();

        return new RedirectResponse('/');
    }
}
