<?php

// $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// remove the directory path the script resides in (if any)
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
$basePath = rtrim($scriptDir, '/');

// remove base path from URI
if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

// ensure it starts with a forward slash
$path = '/' . ltrim($uri, '/');

// include the router file with the Router class definition
require "src/router.php";

// create a new Router object from the Router class
$router = new Router;


// begin adding routes to the router table
$router->add("/", ["controller" => "home", "action" => "index"]);
$router->add("/products", ["controller" => "products", "action" => "index"]);
$router->add("/products/show", ["controller" => "products", "action" => "show"]);

// call to matchRoute() to return an array of $params from $routes
$params = $router->matchRoute($path);

// check for non-existent route
if ($params === false) {

    exit("No matching route");

}

// edit these variables to assign values from $params array from Router class
$controller = $params["controller"];
$action = $params["action"];

// require the necessary controller using the variable value
require "src/controllers/$controller.php";

// assign the name of the desired controller to a $controller_object variable
$controller_object = new $controller;

// call the method from the controller using the $action value
$controller_object->$action();