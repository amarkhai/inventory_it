<?php

namespace App\Application\DTO\Request;

use App\Application\DTO\RequestValidator;
use App\Application\Exceptions\ValidationErrorException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;

abstract class RequestDTO
{
    private array $errors = [];

    public function __construct(
        protected Request $request,
        protected RequestValidator $requestValidator
    ) {
        $this->setValues();
        $this->validate();
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
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

    protected function validate(): void
    {
        $violations = $this->requestValidator->validate($this);
        if (!empty($violations)) {
            $this->setErrors($violations);
            throw new ValidationErrorException($this->request, $violations);
        }
    }

    abstract public function setValues(): void;
}
