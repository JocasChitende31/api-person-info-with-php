<?php

namespace Src\Controller;

use Src\TableGateways\PersonGateways;

class PersonController{

    private $db;
    private $requestMethod;
    private $userId;
    private $personGateway;
    public function __construct($db, $requestMethod, $userId){
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
        
        $this->personGateway = new PersonGateways($this->db);
    }

    public function processRequest(){
        switch ($this->requestMethod){
            case 'GET':
                if($this->userId){
                    $response = $this->getUser($this->userId);
                }else{
                    $response = $this->getAllUsers();
                }
                break;
            case 'POST':
                $response = $this->createUserFromRequest();
                break;
            case 'PUT':
                $response = $this->updateUserFromRequest($this->userId);
                break;
            case 'DELETE':
                $response = $this->deleteUser($this->userId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }

        header($response['status_code_header']);
        if($response['body']){
            echo $response['body'];
        }
    }
    private function getUser($userId): mixed{
        $result = $this->personGateway->findById($userId);
        if(!$result){
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body']               = json_encode($result);
        return $response;
    }
    private function getAllUsers():mixed{
        $result = $this->personGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    
    private function createUserFromRequest():mixed{
        $person = (array) json_decode(file_get_contents('php://person'), TRUE);
        if(!$this->validatePerson($person)){
            return $this->unprocessableEntityResponse();
        }
        $this->personGateway->insert($person);
        $response['status_code_header'] = 'HPPT/1.1 201 CREATED';
        $response['body'] = null;
        return $response;

    }
    private function updateUserFromRequest($id):mixed{

        $restult = $this->personGateway->findById($id);
        if(!$restult){
            return $this->notFoundResponse();
        }
        $person = (array) json_decode(file_get_contents('php://person'), TRUE);
        if(!$this->validatePerson($person)){
            return $this->unprocessableEntityResponse();
        }
        $this->personGateway->update($id, $person);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }
    private function deleteUser($id):mixed{
        $result = $this->personGateway->delete($id);
        if(!$result){
            return $this->notFoundResponse();
        }
        $this->personGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validatePerson(array $person):bool{
        if(isset($person['firstname'])){
            return false;
        }

        if(!isset($person['lastname'])){
            return false;
        }

        if(!isset($person['email'])){
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse():array{
        $response['status_code_header'] = 'HTTP/1.1 402 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid Input',
        ]);
        return $response;
    }
    private function notFoundResponse(): array{
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}