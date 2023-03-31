<?php

namespace App\Application\DTO\Request;

use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

abstract class RequestDTO
{
    protected Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function getBodyParam(string $paramName): ?string
    {
        $requestBody = $this->request->getParsedBody();
        return $requestBody[$paramName] ?? null;
    }

    protected function getRouteParam(string $paramName): ?string
    {
        $routeContext = RouteContext::fromRequest($this->request);
        $route = $routeContext->getRoute();
        return $route?->getArgument($paramName);
    }
}
