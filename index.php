<?php

$uri = explode('/', $_SERVER['REQUEST_URI']);
$page = explode('?', $uri[1])[0];

require_once("db/db_connect.php");

$controller = NULL;

if ($page == "" || $page == "new") {
    require_once("controller/IndexController.php");
    $controller = new IndexController($db, $uri, $page);
} elseif ($page == "connect") {
    require_once("controller/ConnectController.php");
    $controller = new ConnectController($db, $uri);
} elseif ($page == "fractalize") {
    require_once("controller/FractalizeController.php");
    $controller = new FractalizeController($db, $uri);
} elseif ($page == "fractal") {
    require_once("controller/FractalController.php");
    $controller = new FractalController($db, $uri);
} elseif ($page == "user") {
    require_once("controller/UserController.php");
    $controller = new UserController($db, $uri);
} elseif ($page == "admin") {
    require_once("controller/AdminController.php");
    $controller = new AdminController($db, $uri);
}

// debug
elseif ($page == "doc") {
    require_once("doc/index.html");
    exit;
} elseif ($page == "db") {
    require_once("db/db_populate.php");
    exit;
}

else {
    header("HTTP/1.0 404 Not Found");
    exit;
}

$controller->invoke();

