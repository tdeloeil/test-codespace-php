<?php

require_once __DIR__ . '/vendor/autoload.php';

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
$meter = Globals::meterProvider()->getMeter('app-meter');

$tracer = Globals::tracerProvider()->getTracer('demo');


$span = $tracer
    ->spanBuilder('global-span')
    ->startSpan();


$pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);

echo "Connected to database '$dbname' on host '$host' successfully.";

// query table user
$span_sql = $tracer
    ->spanBuilder('sql-query-span')
    ->startSpan();
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$span_sql->end();

var_dump($users);

$logger->info('Fetched users from database', ['user_count' => count($users)]);



$span->end();


exit;

$counter = $meter->createCounter('users_fetched_total', 'users', 'Nombre total de users fetchés');
$counter->add(1, ['environment' => 'development']);

$histogram = $meter->createHistogram('http_request_duration', 'ms', 'Durée des requêtes');
$histogram->record(125.5, ['endpoint' => '/api/users']);

Globals::meterProvider()->forceFlush();
