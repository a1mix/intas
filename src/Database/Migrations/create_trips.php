<?php

namespace App\Database\Migrations;

use PDO;

class create_trips
{
    public function __construct(private PDO $pdo)
    {
    }

    public function up()
    {
        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS trips (
                id serial PRIMARY KEY,
                region_id integer NOT NULL REFERENCES regions(id) ON DELETE CASCADE,
                courier_id integer NOT NULL REFERENCES couriers(id) ON DELETE CASCADE,
                departure_date date NOT NULL,
                arrival_date date NOT NULL
            )
        SQL;

        $this->pdo->exec($sql);

        return $this;
    }

    public function down()
    {
        $sql = <<<SQL
            DROP TABLE IF EXISTS trips
        SQL;

        $this->pdo->exec($sql);

        return $this;
    }
}