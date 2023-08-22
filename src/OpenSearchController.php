<?php

namespace Sav\OpenSearch;

use App\Http\Services\OpenSearchService;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class OpenSearchController extends Controller
{
    protected $opensearchservice;
    public function __construct(OpenSearchService $opensearchservice)
    {
        $this->opensearchservice = $opensearchservice;
    }
    public function test_opensearch()
    {
        try {
            return $this->opensearchservice->testConnection();
        } catch (\Exception $e) {
            return response()->json(["test_opensearch" => $e]);
        }
    }
    public function create_index()
    {
        try {
            $jsonData = file_get_contents('php://input');
            $phpArray = json_decode($jsonData, true);
            return $this->opensearchservice->createIndex($phpArray['indexName'], (object)$phpArray['settings'], (object)$phpArray['mappings']);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function delete_index(Request $request)
    {
        try {
            return $this->opensearchservice->deleteIndex($request->indexName);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function index_document()
    {
        try {
            $jsonData = file_get_contents('php://input');
            $phpArray = json_decode($jsonData, true);
            return $this->opensearchservice->indexDocument($phpArray['indexName'], $phpArray['id'], (object)$phpArray['body']);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function delete_document(Request $request)
    {
        try {
            return $this->opensearchservice->deleteDocument($request->indexName, $request->id);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function search_document()
    {
        try {
            $jsonData = file_get_contents('php://input');
            $phpArray = json_decode($jsonData, true);
            return $this->opensearchservice->searchDocument($phpArray['indexName'], (object)$phpArray['body']);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function get_documents(Request $request)
    {
        try {
            return $this->opensearchservice->getDocuments($request->indexName);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function update_document()
    {
        try {
            $jsonData = file_get_contents('php://input');
            $phpArray = json_decode($jsonData, true);
            return $this->openSearchService->updateDocument($phpArray['indexName'], $phpArray['document_id'], $phpArray['update_fields']);
        } catch (\Exception $e) {
            return $e;
        }
    }
}