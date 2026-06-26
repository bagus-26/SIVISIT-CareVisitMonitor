<?php
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, Accept');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$uri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = dirname($scriptName);
if ($basePath === '\\' || $basePath === '/') $basePath = '';

$endpoint = substr($uri, strlen($basePath));
$endpoint = preg_replace('#^/api_proxy\.php#', '', $endpoint);

$method = $_SERVER['REQUEST_METHOD'];
$data = null;

$rawInput = file_get_contents('php://input');
if ($rawInput) {
    $data = json_decode($rawInput, true);
}

if ($method === 'GET' || $method === 'DELETE') {
    $parsed = parse_url($endpoint);
    $endpoint = $parsed['path'] ?? $endpoint;
    if (!empty($parsed['query'])) {
        parse_str($parsed['query'], $queryData);
        $data = $queryData;
    }
}

$result = callAPI($method, $endpoint, $data);
http_response_code($result['status_code']);
echo json_encode($result['response']);
