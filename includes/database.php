<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $database = getenv('DB_NAME') ?: '';
    $user = getenv('DB_USER') ?: '';
    if ($database === '' || $user === '') {
        throw new RuntimeException('Atur environment variable DB_NAME dan DB_USER terlebih dahulu.');
    }

    $dsn = 'pgsql:dbname=' . $database;
    if ($host = getenv('DB_HOST')) {
        $dsn .= ';host=' . $host;
    }
    if ($port = getenv('DB_PORT')) {
        $dsn .= ';port=' . $port;
    }

    $pdo = new PDO($dsn, $user, getenv('DB_PASSWORD') ?: '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}
