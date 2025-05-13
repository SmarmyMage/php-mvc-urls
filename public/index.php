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
// require "src/Framework/Router.php";

define("ROOT_PATH", dirname(__DIR__));
// add the new WEB_ROOT definition to match the location of your project on the new server
// Change the AvatarName to the avatar name of your project folder on the new server
define("WEB_ROOT", "/webdev/MartyAllen/php-mvc-app/public/");

// remove the following line
// $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// add this line with the WEB_ROOT reference
$path = str_replace(WEB_ROOT, "", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// the autoloader stays the same
spl_autoload_register(function (string $class_name) {

    require ROOT_PATH . "/src/" . str_replace("\\", "/", $class_name) . ".php";
});

$dotenv = new Framework\Dotenv;

$dotenv->load(ROOT_PATH . "/.env");

// print_r($_ENV);

$router = new Framework\Router;


// create a new Router object from the Router class
$router = new Framework\Router;

// begin adding routes to the router table
// $router->add("/", ["controller" => "home", "action" => "index"]);
// $router->add("/products", ["controller" => "products", "action" => "index"]);
// $router->add("/products/show", ["controller" => "products", "action" => "show"]);

$router->add("/product/{slug:[\w-]+}", ["controller" => "products", "action" => "show"]);
$router->add("/{controller}/{id:\d+}/{action}");
$router->add("/home/index", ["controller" => "home", "action" => "index"]);
$router->add("/products", ["controller" => "products", "action" => "index"]);
$router->add("/products/show", ["controller" => "products", "action" => "show"]);
$router->add("/", ["controller" => "home", "action" => "index"]);
$router->add("/{controller}/{action}");

// call to matchRoute() to return an array of $params from $routes
$params = $router->matchRoute($path);
// print_r($params); // Debugging output
// check for non-existent route
if ($params === false) {

    exit("No matching route");
    
}

// add modified code to throw a page not found exception
if ($params === false) {

    throw new PageNotFoundException("No matching route for '$path'.");
    
}

if ( !empty($params["id"]) ) {

    $id = $params["id"];

} else {

    $id = NULL;

}

$action = $params["action"];
$controller = "App\Controllers\\" . ucwords($params["controller"]);

// edit these variables to assign values from $params array from Router class
$controller = $params["controller"];
$action = $params["action"];

// require the necessary controller using the variable value
$controller = "App\Controllers\\" . ucwords($params["controller"]);

// assign the name of the desired controller to a $controller_object variable
$controller_object = new $controller;

// call the method from the controller using the $action value
$controller_object->$action($id);