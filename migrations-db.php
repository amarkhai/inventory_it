<?php
use Doctrine\DBAL\DriverManager;

if ($_ENV['ENV'] === 'TESTING') {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '.env.testing');
} else {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ );
}
$dotenv->load();

return DriverManager::getConnection([
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'host' => $_ENV['DB_HOST'],
    'driver' => $_ENV['DB_DRIVER'],
]);
