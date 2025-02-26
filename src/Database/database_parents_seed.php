<?php
require '../../bootstrap.php';

$statement = <<<EOS
DROP TABLE IF EXISTS parents;
CREATE TABLE IF NOT EXISTS parents (
    id INT NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    number_children INT NOT NULL,
    PRIMARY KEY (id)
) ENGINE = INNODB;

INSERT INTO parents 
    (firstname, lastname, number_children) 
VALUES 
    ('Adelino', 'Chitende', 11),
    ('Anabela', 'Chitende', 8);
   
EOS;

try {
    $createTable = $dbConnection->exec($statement);
    var_dump($createTable);
    echo "Successfully created table!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}