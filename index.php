<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// echo $path. "<br>";
$segments = explode('/', $path);
// print_r($segments);
// get the controller and action from the query string
$controller = $segments[4]; // get the controller name from the URL for server
$action = $segments[5]; // get the action name from the URL for server 
// $controller = $segments[1]; // get the controller name from the URL for local
// $action = $segments[2]; // get the action name from the URL for local

// require the necessary controller using the variable value
require "src/controllers/$controller.php";

// assign the name of the desired controller to a $controller_object variable
$controller_object = new $controller;

// call the method from the controller using the $action value
$controller_object->$action();