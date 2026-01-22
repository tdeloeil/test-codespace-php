<?php

use Monolog\Logger;
use OpenTelemetry\API\Globals;
use OpenTelemetry\Contrib\Logs\Monolog\Handler;
use Psr\Log\LogLevel;

$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

$loggerProvider = Globals::loggerProvider();
$handler = new Handler($loggerProvider, LogLevel::INFO);

$logger = new Logger('otel-logger', [$handler]);

$pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);

echo "Connected to database '$dbname' on host '$host' successfully.";

// query table user
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

var_dump($users);

$logger->info('Fetched users from database', ['user_count' => count($users)]);