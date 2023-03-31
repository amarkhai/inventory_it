<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Application\DTO\Request\RequestDTO;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidator
{
    private ValidatorInterface $validator;

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }


    public function validate(RequestDTO $dto): array
    {
        $violations = [];
        $violationList = $this->validator->validate($dto);

        foreach ($violationList as $violation) {
            $violations[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $violations;
    }
}
