<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$segments = explode('/', $path);

// get the controller and action from the query string
$controller = $segments[1];
$action = $segments[2];

// require the necessary controller using the variable value
require "src/controllers/$controller.php";

// assign the name of the desired controller to a $controller_object variable
$controller_object = new $controller;

// call the method from the controller using the $action value
$controller_object->$action();