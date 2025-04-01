<?php

namespace App\Controllers;

use App\Models\Product;

class Products
{
    public function index()
    {
        // require "src/App/Models/Product.php";

        $model = new Product;

        $products = $model->getData();

        require "views/product-list.php";
    }

    public function show()
    {
        require "views/product-show.php";
    }
}