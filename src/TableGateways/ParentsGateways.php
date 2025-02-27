<?php

namespace Src\TableGateways;

class ParentsGateways
{

    private $db = null;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = " SELECT * FROM parents";
        try {
            $query = $this->db->query($statement);
            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
    public function findById($id)
    {

        $statement = "SELECT * FROM parents WHERE id LIKE ?";

        try {
            $query = $this->db->prepare($statement);
            $query->execute([$id]);
            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
    public function insert(array $parents)
    {
        $statement = "INSERT INTO 
            parents 
                (firstname, lastname, number_children)
            values
                (:firstname, :lastname, :number_children)";

        try {
            $query = $this->db->prepare($statement);
            $executed = $query->execute([
                'firstname' => $parents['firstname'],
                'lastname' => $parents['lastname'],
                'number_children' => $parents['number_children']
            ]);

            if (!$executed) {
                echo "SQL eror:";
                print_r($query->fetchAll(\PDO::FETCH_ASSOC));
                exit();
            }
            return $query->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
    public function update($id, array $parents)
    {
        $statement = "
            UPDATE parents 
            SET 
            firstname = :firstname,
            lastname = :lastname,
            number_children = :number_children
            WHERE id = :id
        ";

        try {
            $query = $this->db->prepare($statement);
            $query->execute([
                'id' => (int) $id,
                'firstname' => $parents['firstname'],
                'lastname' => $parents['lastname'],
                'number_children' => $parents['number_children']
            ]);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
        DELETE 
        FROM
         parents 
         WHERE id = :id
        ";
        try {
            $query = $this->db->prepare($statement);
            $query->execute(['id' => (int) $id]);
            return $query->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
