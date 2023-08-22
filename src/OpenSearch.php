<?php

namespace Sav\OpenSearch;
use \OpenSearch\Common\Exceptions\OpenSearchException;
use \OpenSearch\ClientBuilder;
class OpenSearch
{

    protected $openSearchClient;
    private $host;
    private $port;
    public function __construct()
    {
        return array(
            env('OPENSEARCH_HOST'),env('OPENSEARCH_PORT'),env('OPENSEARCH_USERNAME'), env('OPENSEARCH_PASSWORD')
        );
        $this->openSearchClient = (new ClientBuilder())
            ->setHosts([env('OPENSEARCH_HOST').":".env('OPENSEARCH_PORT')])
            ->setBasicAuthentication(env('OPENSEARCH_USERNAME'), env('OPENSEARCH_PASSWORD'))
            ->setSSLVerification(true)
            ->build();
    }
    public function testConnection()
    {
        try {
            $response = $this->openSearchClient->indices()->get(['index' => '_all']);
            return ($response);
        } catch (OpenSearchException $e) {
            return ($e);
        }
    }
    public function createIndex($indexName, $settings, $mappings)
    {
        try {
            return $this->openSearchClient->indices()->create([
                'index' => $indexName,
                'body' => [
                    'settings' => $settings,
                    'mappings' => $mappings
                ]
            ]);
        } catch (OpenSearchException $e) {
            return ($e);
        }
    }
    public function deleteIndex($indexName)
    {
        try {
            return $this->openSearchClient->indices()->delete([
                'index' => $indexName
            ]);
        } catch (OpenSearchException $e) {
            return ($e);
        }
    }
    public function indexDocument($indexName, $id, $body)
    {
        try {
            return $this->openSearchClient->create([
                'index' => $indexName,
                'id' => $id,
                'body' => $body
            ]);
        } catch (OpenSearchException $e) {
            return ($e);
        }
    }
    public function deleteDocument($indexName, $id)
    {
        try {
            return $this->openSearchClient->delete([
                'index' => $indexName,
                'id' => $id,
            ]);
        } catch (OpenSearchException $e) {
            return ($e);
        }
    }
    public function searchDocument($indexName, $body)
    {
        try {
            return $this->openSearchClient->search([
                'index' => $indexName,
                'body' => $body
            ]);
        } catch (OpenSearchException $e) {
            return ($e);
        }
    }
    public function getDocuments($indexName)
    {
        try {
            return $this->openSearchClient->search([
                'index' => $indexName,
                'body' => [
                    "query" => [
                        "match_all" => (object)[]
                    ]
                ]
            ]);
        } catch (OpenSearchException $e) {
            return ($e);
        }
    }
    public function indexDocuments($indexName, $documents)
    {
        try {
            $params = ['body' => []];
            foreach ($documents as $document) {
                $params['body'][] = [
                    'index' => [
                        '_index' => $indexName,
                        '_id' => $document['id']
                    ]
                ];
                $params['body'][] = $document; // Keep $document as an associative array
            }

            return $this->openSearchClient->bulk($params);
        } catch (OpenSearchException $e) {
            return ($e);
        }
    }

    public function updateDocument($indexName, $id, $updateData)
    {
        try {
            return $this->openSearchClient->update([
                'index' => $indexName,
                'id' => $id,
                'body' => [
                    'doc' => $updateData
                ]
            ]);
        } catch (OpenSearchException $e) {
            return ($e);
        }
    }

}
