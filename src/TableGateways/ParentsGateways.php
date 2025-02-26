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
        $statement = "
            SELECT 
            *
            FROM
            parents
        ";

        try {
            $query = $this->db->query($statement);
            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}