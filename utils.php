<?php

function parse_dns(string $dsn): array {
    $parsed = parse_url($dsn);    
    return [
        'host' => $parsed['host'] ?? 'localhost',
        'port' => $parsed['port'] ?? 25,
        'user' => $parsed['user'] ?? null,
        'pass' => $parsed['pass'] ?? null,
        'scheme' => $parsed['scheme'] ?? 'smtp',
    ];
}

