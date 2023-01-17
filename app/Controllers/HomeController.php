<?php

namespace App\Controllers;

use Slim\Http\ServerRequest as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Controllers\AbstractController;


class HomeController extends AbstractController
{


    public function __construct($container)
    {
        parent::__construct($container);
    }

    /**
     * Return the "Status" view
     * @param Request $request
     * @param Response $response
     * @return HTML
     */
    public function status(Request $request, Response $response)
    {
        return $this->withJSON($response,["status"=>"OK"]);
    }
    
    public function convert(Request $request, Response $response)
    {
        return $this->withJSON($response,["status"=>"OK"]);
    }


}
