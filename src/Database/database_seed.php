<?php
require 'bootstrap.php';

$statement = <<<EOS 
    DROP TABLE IF EXISTS person;
    CREATE TABLE IF NOT EXISTS person (
        id INT NOT NULL AUTO_INCREMENT,
        firstname VARCHAR(255) NOT NULL,
        lastname VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        firstparent_id INT  DEFAULT NULL,
        secondparent_id INT DEFAULT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (firstparent__id)
            REFERENCES person(id)
            ON DELETE SET NULL,
        FOREIGN KEY (secondparent_id)
            REFERENCES person(id)
            ON DELETE SET NULL
    ) ENGINE=INNODB;

    INSERT INTO person 
        (id, firstname, lastname, email, firstparent_id, secondparent_id)
    VALUES 
        (1, 'Jeorgel', 'Chitende', null, null),
        (2, 'Alhures', 'Chitende', null, null),
        (3, 'Negas', 'Chitende', null, null);
EOS;

try{
    $createTable = $dbConnection->exec($statemant);
    echo "Successfully created table!\n";
}catch(\PDOException $e){
    exit($e->getMessage());
}