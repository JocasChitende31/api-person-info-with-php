<?php

namespace Src\Controller;

use Src\TableGateways\ParentsGateways;

class ParentsController
{

    private $db;
    private $parentId;
    private $requestMethod;
    private $parentsGateway;

    public function __construct($db, $requestMethod, $parentId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->parentId = $parentId;

        $this->parentsGateway = new ParentsGateways($this->db);
    }
    public function processRequest()
    {
        switch ($this->requestMethod) {

            case 'GET':
                $this->parentId ? $response = $this->getParent($this->parentId) : $response = $this->getAllParents();
                break;
            case 'POST':
                $response = $this->createParentFromRequest();
                break;
            case 'PUT':
                $response = $this->updateParentFromRequest($this->parentId);
                break;
            case 'DELETE':
                $response = $this->deleteParent($this->parentId);
                break;
            default;
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
    private function getParent($parentId): mixed
    {
        $result = $this->parentsGateway->findById($parentId);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function getAllParents(): mixed
    {
        $result = $this->parentsGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function createParentFromRequest(): mixed
    {
        $input = (array) json_decode(file_get_contents('php://input'), true);
        if (!$this->validateParent($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->parentsGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }
    private function updateParentFromRequest($parentId): mixed
    {
        $result = $this->parentsGateway->findById($parentId);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), true);
        if (!$this->validateParent($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->parentsGateway->update($parentId, $input);
        $response['satatus_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }
    private function deleteParent($parentId): mixed
    {
        $result = $this->parentsGateway->findById($parentId);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $this->parentsGateway->delete($parentId);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }
    private function validateParent($input): bool
    {
        if (empty($input['firstname']) || empty($input['lastname']) || empty($input['number_children'])) {
            return false;
        }

        return true;
    }
    private function unprocessableEntityResponse(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode(['error' => 'Invalid input.']);
        return $response;
    }
    private function notFoundResponse(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}
