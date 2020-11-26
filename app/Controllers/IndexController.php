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

        $session_record = $request->getAttribute('session');
        $id_session = $session_record->id_session;
        $id_visitor = $session_record->id;
        $updated_at = $session_record->updated_at;
        $data = [
            'url'        => $url,
            'id_session' => $id_session,
            'id_visitor' => $id_visitor,
            'updated_at' => $updated_at
        ];

        return $this->container->get('view')->render($response, 'index.twig', $data);

    }
}