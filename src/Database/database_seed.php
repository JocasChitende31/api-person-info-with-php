<?php
// require 'bootstrap.php';
require '../../bootstrap.php';

$statement = <<<EOS
DROP TABLE IF EXISTS person;
CREATE TABLE IF NOT EXISTS person (
    id INT NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    firstparent_id INT DEFAULT NULL,
    secondparent_id INT DEFAULT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (firstparent_id)
        REFERENCES person(id)
        ON DELETE SET NULL,
    FOREIGN KEY (secondparent_id)
        REFERENCES person(id)
        ON DELETE SET NULL
) ENGINE=INNODB;

INSERT INTO person 
    (firstname, lastname, email, firstparent_id, secondparent_id)
VALUES 
    ('Jeorgel', 'Chitende', 'jeorgel@example.com', null, null),
    ('Alhures', 'Chitende', 'alhures@example.com', null, null),
    ('Negas', 'Chitende', 'negas@example.com', null, null);

EOS;

try {
    $createTable = $dbConnection->exec($statement);
    echo "Successfully created table!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}