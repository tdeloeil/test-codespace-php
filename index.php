<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once "./utils.php";

use Monolog\Logger;
use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Trace\SpanKind;
use OpenTelemetry\Contrib\Logs\Monolog\Handler;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Log\LogLevel;

$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$mailer_dsn = getenv('MAILER_DSN');

//die('banana');

$config_dsn = parse_dns($mailer_dsn);

// Init Telemetry
$loggerProvider = Globals::loggerProvider();
$handler = new Handler($loggerProvider, LogLevel::INFO);

$logger = new Logger('otel-logger', [$handler]);
$meter = Globals::meterProvider()->getMeter('app-meter');

$tracer = Globals::tracerProvider()->getTracer('demo');
$span = $tracer
    ->spanBuilder('global-span')
    ->startSpan();
$scope = $span->activate();

// Init Mailer
$mail = new PHPMailer(true);

// Init Database
$pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);

// TODO: Test Something
// DEBUG: F5

// Query table user
$span_sql = $tracer
    ->spanBuilder('sql-query-span')
    ->setSpanKind(SpanKind::KIND_CLIENT)
    ->startSpan();
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
$span_sql->end();

// Handler users
$json = json_encode($users);
$logger->info('Fetched users from database', ['user_count' => count($users)]);

// Send email
try {
    $mail->isSMTP();
    $mail->Host = $config_dsn["host"];
    $mail->Port = $config_dsn["port"];
    $mail->SMTPAuth = false;

    $mail->setFrom('test@example.com', 'Testeur');
    $mail->addAddress('destinataire@test.com', 'Destinataire');

    $mail->isHTML(true);
    $mail->Subject = 'Email de test PHPMailer';
    $mail->Body    = "<h1>test mail</h1>$json";

    $mail->send();
} catch (Exception $e) {
    $logger->error("Send email", [
        'err' => $e->getMessage()
    ]);
}

// Response
header('Content-Type: application/json; charset=utf-8');
echo $json;


// End tracing
$span->end();
$scope->detach();














exit;

$counter = $meter->createCounter('users_fetched_total', 'users', 'Nombre total de users fetchés');
$counter->add(1, ['environment' => 'development']);

$histogram = $meter->createHistogram('http_request_duration', 'ms', 'Durée des requêtes');
$histogram->record(125.5, ['endpoint' => '/api/users']);

Globals::meterProvider()->forceFlush();
