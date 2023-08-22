<?php

namespace Sav\OpenSearch;

use App\Http\Services\OpenSearch;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class OpenSearchController extends Controller
{
    protected $OpenSearch;
    public function __construct(OpenSearch $OpenSearch)
    {
        $this->OpenSearch = $OpenSearch;
    }
    public function test_opensearch()
    {
        try {
            return $this->OpenSearch->testConnection();
        } catch (\Exception $e) {
            return response()->json(["test_opensearch" => $e]);
        }
    }
    public function create_index()
    {
        try {
            $jsonData = file_get_contents('php://input');
            $phpArray = json_decode($jsonData, true);
            return $this->OpenSearch->createIndex($phpArray['indexName'], (object)$phpArray['settings'], (object)$phpArray['mappings']);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function delete_index(Request $request)
    {
        try {
            return $this->OpenSearch->deleteIndex($request->indexName);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function index_document()
    {
        try {
            $jsonData = file_get_contents('php://input');
            $phpArray = json_decode($jsonData, true);
            return $this->OpenSearch->indexDocument($phpArray['indexName'], $phpArray['id'], (object)$phpArray['body']);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function delete_document(Request $request)
    {
        try {
            return $this->OpenSearch->deleteDocument($request->indexName, $request->id);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function search_document()
    {
        try {
            $jsonData = file_get_contents('php://input');
            $phpArray = json_decode($jsonData, true);
            return $this->OpenSearch->searchDocument($phpArray['indexName'], (object)$phpArray['body']);
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function get_documents(Request $request)
    {
        try {
            return $this->OpenSearch->getDocuments($request->indexName);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function update_document()
    {
        try {
            $jsonData = file_get_contents('php://input');
            $phpArray = json_decode($jsonData, true);
            return $this->OpenSearch->updateDocument($phpArray['indexName'], $phpArray['document_id'], $phpArray['update_fields']);
        } catch (\Exception $e) {
            return $e;
        }
    }
}