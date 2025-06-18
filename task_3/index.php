<?php
require __DIR__ . '/../task_1/vendor/autoload.php';

use Heliostat\Task1\Container;
use Heliostat\Task3\{ClickService, ClickRepository, FinanceClient};

$container = new Container();
$container->singleton(ClickRepository::class);
$container->singleton(FinanceClient::class);
$container->register(ClickService::class);

$service = $container->get(ClickService::class);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($path === '/webhook' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $service->accept($data);
    echo json_encode(['status' => 'ok']);
} elseif ($path === '/stats' && $method === 'GET') {
    $from = $_GET['from'] ?? date('Y-m-d');
    $to = $_GET['to'] ?? date('Y-m-d');
    $sort = $_GET['sort'] ?? 'offer_id';
    echo json_encode($service->stats($from, $to, $sort));
} elseif ($path === '/forward' && $method === 'POST') {
    $date = $_GET['date'] ?? date('Y-m-d');
    $clicks = $service->byDate($date);
    $client = $container->get(FinanceClient::class);
    $client->send($clicks);
    echo json_encode(['status' => 'forwarded']);
} else {
    http_response_code(404);
    echo 'Not Found';
}