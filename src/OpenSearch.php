<?php

namespace Sav\OpenSearch;
use \OpenSearch\Common\Exceptions\OpenSearchException;
use \OpenSearch\ClientBuilder;
class OpenSearch
{

    protected $openSearchClient;
    private $host;
    private $port;
    private $username;
    private $secrete;
    public function __construct()
    {
        $this->host = constants('opensearch.host');
        $this->port = constants('opensearch.port');
        $this->username = constants('opensearch.username');
        $this->secrete = constants('opensearch.password');
        
        $this->openSearchClient = (new ClientBuilder())
            ->setHosts([$this->host.":".$this->port])
            ->setBasicAuthentication($this->username, $this->secrete)
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
             throw $e;
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

    public function cloneIndex($source, $destination)
    {
        try {

            $this->makeWritable(true);
            $this->openSearchClient->indices()->clone([
                'index' => $source,
                'target' => $destination,
            ]);

            $this->makeWritable(false);
  
        } catch (OpenSearchException $e) {
            return ($e);
        }
    }

    public function renameIndex($source, $destination)
    {
        try {
            $this->makeWritable(true);
            $this->openSearchClient->indices()->shrink([
                'index' => $source,
                'target' => $destination,
            ]);
            $this->makeWritable(false);
        } catch (OpenSearchException $e) {
            return ($e);
        }
    }

    private function makeWritable($index, $bool){
        $params = [
            'index' => $index,
            'body' => [
                'settings' => [
                    'index.blocks.write' => $bool
                ]
            ]
        ];
        $this->openSearchClient->indices()->putSettings($params);
        return true;
    }

}
