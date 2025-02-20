<?php
namespace Src\TableGateways;

class PersonGateways {
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
    public function findById($id){
        $statement = "
        SELECT 
            *
        FROM
            person
        WHERE id LIKE ?;
        ";

        try{
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        }catch(\PDOException $e){
            exit($e->getMessage());
        }
    }

    public function insert( array $person){
        $statement = "
        INSERT INTO person 
            (firstname, lastname, email, firstparent_id, secondparent_id)
        VALUES ( :firstname, :lastname, :email, :firstparent_id, :secondparent_id );
        ";

        try{
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'firstname' => $person['firstname'],
                'lastname' => $person('lastname'),
                'eamil' => $person['email'],
                'firstparent_id' => $person['firstparent_id'] ?? null,
                'secondparent_id' => $person['secondparent_id'] ?? null,
            ));
            return $statement->rowCount();
        }catch(\PDOException $e){
            exit($e->getMessage());
        }
    }

    public function update($id, array $person){
        $statement = "
        UPDATE person
        SET
        firstname = :firstname,
        lastname = :lastname,
        email = :email,
        firstparent_id = :firstparent_id,
        secondparent_id = :secondparent_id,
        WHERE id = :id;
        ";

        try{
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
            'id' => (int) $id,
            'firstname' => $person['firstname'],
            'lastname' => $person['lastname'],
            'email' => $person['email'],
            'firstparent_id' => $person['firstparent_id']?? null,
            'secondparent_id' => $person['secondparent_id']?? null,
            
            ));
        }catch(\PDOException $e){
            exit($e->getMessage());
        }
    }
    public function delete($id){
        $statement = "
        DELETE FROM person
        WHERE id = :id
        ";

        try{
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id'=> (int) $id));
            return $statement->rowCount();
        }catch(\PDOException $e){
            exit($e->getMessage());
        }
    }
}