<?php

namespace App\Database;

use PDO;

final class Connection
{
    private static ?Connection $instance = null;

    private ?PDO $pdo = null;

    private function __construct()
    {
    }

    public static function get(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function connect(): PDO
    {
        if ($this->pdo === null) {
            $params = parse_ini_file('database.ini');
            if ($params === false) {
                throw new \Exception("Error reading database configuration file");
            }

            $conStr = sprintf(
                "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
                $params['host'],
                $params['port'],
                $params['database'],
                $params['user'],
                $params['password']
            );

            $this->pdo = new PDO($conStr);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $this->pdo;
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }
}