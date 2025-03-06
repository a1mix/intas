<?php

namespace App\Database\Migrations;

use PDO;

class create_couriers
{
    public function __construct(private PDO $pdo)
    {
    }

    public function up()
    {
        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS couriers (
            id serial PRIMARY KEY,
            name character varying(255) NOT NULL UNIQUE)
        SQL;

        $this->pdo->exec($sql);

        return $this;
    }

    public function down() {
        $sql = <<<SQL
            DROP TABLE IF EXISTS couriers
        SQL;

        $this->pdo->exec($sql);

        return $this;
    }
}