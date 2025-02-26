<?php
require "../bootstrap.php";

use Src\Controller\PersonController;
use Src\Controller\ParentsController;

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($uri, '/')); // Remove barras extras no início e no fim

// Verifica se há pelo menos um segmento válido
if (!isset($uri[0]) || ($uri[0] !== 'person' && $uri[0] !== 'parent')) {
    header('HTTP/1.1 404 Not Found');
    echo json_encode(["error" => "Endpoint not found"]);
    exit();
}

$userId = null;
$parentId = null;

if ($uri[0] === 'person' && isset($uri[1])) {
    $userId = (int) $uri[1];
}

if ($uri[0] === 'parent' && isset($uri[1])) {
    $parentId = (int) $uri[1];
}

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Inicializa o controlador correto
if ($uri[0] === 'person') {
    $controller = new PersonController($dbConnection, $requestMethod, $userId);
} else {
    $controller = new ParentsController($dbConnection, $requestMethod, $parentId);
}

$controller->processRequest();
