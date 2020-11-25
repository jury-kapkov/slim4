<?php


namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

class IndexController extends BaseController
{

    public function index(Request $request, Response $response, $args)
    {
        //Get url to index page
        $routeContext = RouteContext::fromRequest($request);
        $routeParser = $routeContext->getRouteParser();
        $url = $routeParser->urlFor('index');

        return $this->container->get('view')->render($response, 'index.twig', ['url' => $url]);

    }
}