<?php
require Src\TableGateways;

class PersonGateway {
    private $db = null;
    public function __constructor($db){
        $this->db = $db;
    }

    public function findAll(){
        $statement = "
        SELECT 
            *
        FROM 
            person;
        ";

        try{
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        }catch(\PDOException $e){
            exit($e->getMessage());
        }
    }
}