<?php

namespace App\Controllers;

use Slim\Http\ServerRequest;
use App\Controllers\AbstractController;
use \Psr\Http\Message\ResponseInterface;

class HomeController extends AbstractController
{


    public function __construct($container)
    {
        parent::__construct($container);
    }

    /**
     * Return the "Home" view
     * @param Request $request
     * @param Response $response
     * @return HTML
     */
    public function home(ServerRequest $request, ResponseInterface $response)
    {
        return $this->withJSON($response,["status"=>"OK"]);
    }

}
