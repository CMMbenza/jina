<?php
// config/config.php

$envFile = __DIR__ . '/../.env';

if (!file_exists($envFile)) {
    throw new Exception("Le fichier .env est introuvable.");
}

$config = [];

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {

    $line = trim($line);

    if ($line === '' || str_starts_with($line, '#')) {
        continue;
    }

    if (!str_contains($line, '=')) {
        continue;
    }

    [$key, $value] = explode('=', $line, 2);

    $config[trim($key)] = trim($value);
}

define('DB_HOST', $config['DB_HOST'] ?? '');
define('DB_USER', $config['DB_USER'] ?? '');
define('DB_PASS', $config['DB_PASS'] ?? '');
define('DB_NAME', $config['DB_NAME'] ?? '');

try {

    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

    $pdo = new PDO(
        $dsn,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

} catch (PDOException $e) {

    throw new Exception("Connexion à la base de données impossible : " . $e->getMessage());
}