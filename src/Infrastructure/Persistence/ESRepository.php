<?php

namespace App\Infrastructure\Persistence;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;

abstract class ESRepository
{
    public function __construct(
        protected readonly Client $client
    ) {
    }

    /**
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    protected function search(array $query, int $limit = 20, int $from = 0): array
    {
        $params = [
            'index' => $this->getIndexName(),
            'body' => [
                'from' => $from,
                'size' => $limit,
                'query' => $query
            ]
        ];

        $result = $this->client->search($params);

        // для удовлетворения анализаторов кода
        if (method_exists($result, 'asArray')) {
            return $result->asArray();
        }
        throw new ServerResponseException();
    }

    protected function getFoundedItems(array $searchResult): array
    {
        if (!isset($searchResult['hits']) || !isset($searchResult['hits']['hits'])) {
            return [];
        }
        return $searchResult['hits']['hits'];
    }

    abstract protected function getIndexName(): string;
}
