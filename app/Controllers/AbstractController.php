<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Slim\Http\ServerRequest;
use Slim\Routing\RouteContext;
use GuzzleHttp\Psr7\LazyOpenStream;
use Slim\Psr7\Factory\StreamFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
/**
 * This abstract class defines methods and properties used by all controllers.
 *
 * @package App\Controllers
 */
abstract class AbstractController
{
    protected $container;
    protected $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger = $container->get(LoggerInterface::class);
    }

    /**
     * getUrlFor - Return url for named route
     *
     * @param  ServerRequest $request
     * @param  string $route
     * @return string
     */
    protected function getUrlFor(ServerRequest $request, string $route): string
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $UrlFor = $routeParser->urlFor($route);
        return $UrlFor;
    }

    /**
     * withRedirect - Send a redirect, default code is 302
     *
     * @param  mixed $response
     * @param  mixed $path
     * @param  mixed $code
     * @return Response
     */
    protected function withRedirect(ResponseInterface $response, string $path, int $code = 302): ResponseInterface
    {
        $response = $response
            ->withHeader('Cache-Control', 'no-cache, must-revalidate')
            ->withHeader('Expires','Mon, 26 Jul 1997 05:00:00 GMT')
            ->withHeader('Location', $path)
            ->withStatus($code);
        return $response;
    }


    /**
     * withJpeg - Display JPEG
     *
     * @param  mixed $response
     * @param  string $jpegdata
     * @return Response
     */
    protected function withJpeg(ResponseInterface $response, string $jpegdata): ResponseInterface
    {
        
        $stream = (new StreamFactory())->createStream($jpegdata, 'rb');
        $response = $response
            ->withAddedHeader('Cache-Control', 'public')
            ->withAddedHeader('Cache-Control', 'max-age=86400')
            ->withAddedHeader('Content-type', 'image/jpeg')
            ->withStatus(200)
            ->withBody($stream);
        return $response;
    }

    /**
     * withPng - Display PNG
     *
     * @param  mixed $response
     * @param  string $pngdata
     * @return Response
     */
    protected function withPng(ResponseInterface $response, string $pngdata): ResponseInterface
    {
        $stream = (new StreamFactory())->createStream($pngdata, 'rb');

        $response = $response
            ->withAddedHeader('Cache-Control', 'public')
            ->withAddedHeader('Cache-Control', 'max-age=86400')
            ->withAddedHeader('Content-type', 'image/png')
            ->withBody($stream)
            ->withStatus(200);
        return $response;
    }

    protected function withZip(ResponseInterface $response, string $filename): ResponseInterface
    {
        $stream = new LazyOpenStream($filename, 'rb');
        $zipfilesize = filesize($filename);
        //unlink($filename); //delete temporary file
        return $response->withHeader('Content-type', 'application/zip')
            ->withAddedHeader("Content-length", $zipfilesize)
            ->withAddedHeader('Expires', ' 0')
            ->withAddedHeader('Content-Type', 'application/download')
            ->withAddedHeader('Content-Description', 'File Transfer')
            ->withAddedHeader('Content-Transfer-Encoding', 'binary')
            ->withAddedHeader('Content-Disposition', 'attachment; filename="' . basename($filename) . '"')
            ->withAddedHeader('Cache-Control', 'no-cache')
            ->withAddedHeader('Cache-Control', 'must-revalidate')
            ->withAddedHeader('Cache-Control', 'post-check=0')
            ->withAddedHeader('Cache-Control', 'pre-check=0')
            ->withBody($stream); // all stream contents will be sent to the response
    }

    /**
     * Return the payload as JSON
     * @author Pierre Christensen <pierre.christensen@gmail.com>
     * @param Response $response
     * @param array    $payload
     * @return Response
     */
    protected function withJSON(ResponseInterface $response, array $payload = []): ResponseInterface
    {
        $stream = (new StreamFactory())->createStream(json_encode($payload), 'rb');

        $response = $response
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Cache-Control', 'no-cache, must-revalidate')
            ->withHeader('Expires','Mon, 26 Jul 1997 05:00:00 GMT')
            ->withBody($stream)
            ->withStatus(200);
        return $response;
    }
}
