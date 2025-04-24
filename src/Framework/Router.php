<?php

namespace Framework;

class Router
{
    // create array to hold routes from route table
    private array $routes = [];

    // modify the $params to default an empty array
    public function add(string $path, array $params = []): void
    {
        $this->routes[] = [
            "path" => $path,
            "params" => $params
        ];
    }

    // create method to check a URL for a matching route from the routes table
    public function matchRoute(string $path): array|bool
    {
        // decodes special characters in URL
        $path = urldecode($path);

        // trims the preceding forward slash / from URL segments
        $path = trim($path, "/");

        // step through the routes table for matching route
        foreach ($this->routes as $route) {

            // call getURLData method to process route path from route table
            $pattern = $this->getURLData($route["path"]);

            if (preg_match($pattern, $path, $matches)) {

                $matches = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);

                // Ensure all parameters are strings
                $matches = array_map('strval', $matches);

                // merge existing simple URL patterns with new variable patterns
                $params = array_merge($matches, $route["params"]);

                // print_r($params); // Debugging output

                return $params;
            }
        }

        return false;
    }

    // proces URL for data segments
    private function getURLData(string $route_path)
    {
        $route_path = trim($route_path, "/");

        $segments = explode("/", $route_path);

        $segments = array_map(function (string $segment): string {

            // matches simple variables
            if (preg_match("#^\{([a-z][a-z0-9]*)\}$#", $segment, $matches)) {

                return "(?<" . $matches[1] . ">[^/]*)";

            }

            // matches complex variables with names (id and slug)
            if (preg_match("#^\{([a-z][a-z0-9]*):(.+)\}$#", $segment, $matches)) {

                // separates segment name from segment data
                return "(?<" . $matches[1] . ">" . $matches[2] . ")";

            }

            return $segment;

        }, $segments);

        // creates desired regular expression for URL variables
        return "#^" . implode("/", $segments) . "$#iu";
    }
}