<?php

declare(strict_types=1);

namespace App\Application\DTO\Request\Item;

use App\Application\DTO\Request\AuthenticatedRequestDTO;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Validator\Constraints as Assert;

class ListItemsRequestDTO extends AuthenticatedRequestDTO
{
    #[Assert\Type("int")]
    private ?int $rootItemId = null;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $rootItemId = $this->getBodyParam('rootItemId');
        if (is_numeric($rootItemId)) {
            $this->rootItemId = (int) $rootItemId;
        }
    }

    /**
     * @return int|null
     */
    public function getRootItemId(): ?int
    {
        return $this->rootItemId;
    }
}
