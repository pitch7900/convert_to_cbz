<?php

namespace App\Middleware;


use Slim\App;
use App\Authentication\Authentication;
use Slim\Psr7\Factory\ResponseFactory;
use App\Controllers\AbstractController;
use \Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use \Psr\Http\Message\ServerRequestInterface;

/**
 * Description of AuthMiddleware
 *
 * @author pierre
 */
/* Extending the AbstractController class. */
class AuthMiddleware extends AbstractController
{
    /**
     * Logger interface
     * @var LoggerInterface;
     */
    
    private $app;


    /**
     * @param App    $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $container = $this->app->getContainer();
    

        parent::__construct($container);
    }
    
    public function __invoke(ServerRequestInterface  $request, RequestHandlerInterface  $handler): ResponseInterface
    {
        $this->logger->debug("AuthMiddleware::__invoke " . getenv('apptoken'));
        if (!Authentication::IsAuthentified($this->logger)) {
            $responseFactory = new ResponseFactory();
            $response = $responseFactory->createResponse();
            return $this->withStatus($response,403,"Not allowed");
        }

        $response = $handler->handle($request);
        return $response;
    }
}
