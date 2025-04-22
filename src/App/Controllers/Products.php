<?php

namespace App\Controllers;

use App\Models\Product;
use Framework\Viewer;

class Products
{
    public function index()
    {
        // create new model object
        $model = new Product;

        // call getData() method from product model
        // assign result set to $products variable
        $products = $model->getData();

        if ($products === false) {

            throw new PageNotFoundException("Product not found");
            
        }
                $viewer = new Viewer;

        echo $viewer->render("shared/header.php", [
            "title" => "Products"
        ]);

        // pass db result set to view in array named "products"
        echo $viewer->render("Products/index.php", [
            "products" => $products
        ]);
    }

    public function show(string $id = NULL)
    {
        $model = new Product;

        $product = $model->find($id);

        if ($product === false) {

            throw new PageNotFoundException("Product not found");
            
        }        $viewer = new Viewer;

        echo $viewer->render("shared/header.php", [
            "title" => "Product"
        ]);

        // pass db result set to view in array named "product"
        echo $viewer->render("Products/show.php", [
            "product" => $product
        ]);
    }
}