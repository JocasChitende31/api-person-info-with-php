<?php

namespace Src\TableGateways;

class PersonGateways
{
    private $db = null;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
        SELECT  
        * 
        FROM 
        person";

        echo "FindAll method";
        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
    public function findById($id)
    {
        $statement = "SELECT * FROM person WHERE id LIKE ?
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute([$id]);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function insert(array $input)
    {
        $statement = "INSERT INTO person(firstname, lastname, email, firstparent_id, secondparent_id)
        VALUES (:firstname, :lastname, :email, :firstparent_id, :secondparent_id)
        ";
        try {
            $query = $this->db->prepare($statement);
            $executed = $query->execute([
                'firstname' => $input['firstname'],
                'lastname' => $input['lastname'],
                'email' => $input['email'],
                'firstparent_id' => $input['firstparent_id'] ?? null,
                'secondparent_id' => $input['secondparent_id'] ?? null,
            ]);
            if (!$executed) {
                echo "SQL Error: ";
                print_r($query->errorInfo()); // 🔴 Exibe erro SQL detalhado
                exit();
            }
            return $query->rowCount();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw new \Exception('Erro ao inserir no banco de dados!');
        }
    }

    public function update($id, array $person)
    {
        $statement = "
        UPDATE person
        SET
        firstname = :firstname,
        lastname = :lastname,
        email = :email,
        firstparent_id = :firstparent_id,
        secondparent_id = :secondparent_id
        WHERE id = :id
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute([
                'id' => (int) $id,
                'firstname' => $person['firstname'],
                'lastname' => $person['lastname'],
                'email' => $person['email'],
                'firstparent_id' => $person['firstparent_id'] ?? null,
                'secondparent_id' => $person['secondparent_id'] ?? null,

            ]);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
    public function delete($id)
    {
        $statement = "
        DELETE FROM person
        WHERE id = :id
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(['id' => (int) $id]);
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
