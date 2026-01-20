<?php

$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

$pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);

echo "Connected to database '$dbname' on host '$host' successfully.";

// query table user
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

var_dump($users);
