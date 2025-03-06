<?php

namespace App\Database\Migrations;

use PDO;

class create_regions
{
    public function __construct(private PDO $pdo)
    {
    }

    public function up()
    {
        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS regions (
                id serial PRIMARY KEY,
                name character varying(255) NOT NULL UNIQUE,
                travel_duration integer NOT NULL
            )
        SQL;

        $this->pdo->exec($sql);

        return $this;
    }

    public function down()
    {
        $sql = <<<SQL
            DROP TABLE IF EXISTS regions
        SQL;

        $this->pdo->exec($sql);

        return $this;
    }
}