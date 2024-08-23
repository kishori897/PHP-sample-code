<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/UserController.php';

$database = new Database();
$db = $database->getConnection();
$controller = new UserController($db);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Adjust the URI if it contains the base path
$base_path = '/PHP-sample-code/public/index.php';
$uri = str_replace($base_path, '', $uri);


if ($uri == '/users') {
    $controller->index();
} elseif ($uri == '/users/create') {
    $controller->create();
} elseif ($uri == '/users/edit' && isset($_GET['id'])) {
    $controller->edit($_GET['id']);
} elseif ($uri == '/users/delete' && isset($_GET['id'])) {
    $controller->delete($_GET['id']);
}  elseif ($uri == '/users/change-password' && isset($_GET['id'])) {
    $controller->changePassword($_GET['id']);
}else {
    echo "404 Not Found";
}
