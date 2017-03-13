<?php

namespace App\Controller;

use \PDO;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Cabinet
{
    public function getIndex($request, $response)
    {
        $logged = $request->getSession()->get('logged');
        if ($logged) {
            $response->setContent('CABINET');
        } else {
            $response->setStatusCode('403');
            $response->setContent('Forbidden.');
        }

        return $response;
    }

    // public function postAddItem($request, $response)
    // {
    //     $logged = $request->getSession()->get('logged');
    //     if ($logged) {
    //         $response->setContent('CABINET');
    //     } else {
    //         $response->setStatusCode('403');
    //         $response->setContent('Forbidden.');
    //     }

    //     return $response;
    // }
}
