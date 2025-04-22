<?php

namespace App\Models;

use PDO;

class Product
{
    // connect to the database
    public function getConnection()
    {
        $dsn = "mysql:
                host=localhost;
                dbname=sherd_MartyAllen;
                charset=utf8;
                port=3306";

        return new PDO($dsn, "sherd_MartyAllen", "KUE7r2kX34kf3", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    // method to gat all rows of data from the products table
    public function getData(): array
    {
        // establish db connection
        $conn = $this->getConnection();

        // create db query
        $sql = "SELECT * FROM `products`";

        // send query to db
        $stmt = $conn->prepare($sql);

        // execute query
        $stmt->execute();

        // return db result set to Products controller as associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // method to get a row(s) from db based on a specific ID 
    // passed from the index.php page in the site root directory
    public function find(string $id): array|bool
    {
        $conn = $this->getConnection();

        $sql = "SELECT * FROM `products` WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}