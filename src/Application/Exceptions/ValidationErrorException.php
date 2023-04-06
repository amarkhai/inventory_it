<?php

namespace App\Application\Exceptions;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpSpecializedException;
use Throwable;

class ValidationErrorException extends HttpSpecializedException
{
    private array $errors;
    /**
     * @var int
     */
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'Validation failed.';
    public function __construct(
        ServerRequestInterface $request,
        array $errors = [],
        ?string $message = null,
        ?Throwable $previous = null
    ) {
        if ($message !== null) {
            $this->message = $message;
        }
        $this->errors = $errors;

        parent::__construct($request, $this->message, $previous);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
