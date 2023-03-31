<?php

declare(strict_types=1);

namespace App\Application\DTO\Request\Item;

use App\Application\DTO\Request\AuthenticatedRequestDTO;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Validator\Constraints as Assert;

class ViewItemRequestDTO extends AuthenticatedRequestDTO
{
    #[Assert\NotBlank]
    #[Assert\Type("int")]
    private ?int $itemId = null;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $itemId = $this->getRouteParam('id');
        if (is_numeric($itemId)) {
            $this->itemId = (int) $itemId;
        }
    }

    /**
     * @return int
     */
    public function getItemId(): int
    {
        return $this->itemId;
    }
}
