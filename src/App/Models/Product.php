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
        // die($id);
        // var_dump($id);
        $conn = $this->getConnection();

        $sql = "SELECT * FROM `products` WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // var_dump($result);

        return $result;
    }

    public function update(string $id, array $data): bool
    {
        if ( ! $this->validate($data) ) {
            return false;
        }

        unset($data["id"]);

        $fields = array_keys($data);

        array_walk($fields, function (&$value) {
            $value = "$value = ?";
        });

        $sql = "UPDATE `products` SET " . implode(", ", $fields) . " WHERE id = ?";

        $conn = $this->getConnection();

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(1, $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(2, $data["description"], PDO::PARAM_STR);
        $stmt->bindValue(3, $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function validate(array $data): bool
    {
        // check if name is empty
        if (empty($data["name"])) {
            return false;
        }

        // check if description is empty
        if (empty($data["description"])) {
            return false;
        }

        return true;
    }

    public function insert()
    {
        // create a data array to hold form field data
        $data = [
            "name" => $_POST["name"],
            "description" => $_POST["description"]
        ];

        // validate the data
        if ( ! $this->validate($data) ) {
            return false;
        }

        // create a string of field names for the SQL statement
        $fields = implode(", ", array_keys($data));

        // create a string of placeholders for the SQL statement
        $placeholders = ":" . implode(", :", array_keys($data));

        // create the SQL statement
        $sql = "INSERT INTO `products` ($fields) VALUES ($placeholders)";

        // establish db connection
        $conn = $this->getConnection();

        // prepare the SQL statement
        $stmt = $conn->prepare($sql);

        // bind the values to the placeholders in the SQL statement
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
        }

        // execute the SQL statement and return true on success or false on failure
        return $stmt->execute();
    }

    public function edit(string $id = NULL)
    {
        $model = new Product;

        $product = $model->find($id);

        if ($product === false) {

            throw new PageNotFoundException("Product not found");

        }

        $viewer = new Viewer;

        echo $viewer->render("shared/header.php", [
            "title" => "Edit Product"
        ]);

        echo $viewer->render("Products/edit.php", [
            "product" => $product
        ]);
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM `products` WHERE id = :id";

        $conn = $this->getConnection();

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}