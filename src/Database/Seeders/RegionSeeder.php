<?php

namespace App\Database\Seeders;

use PDO;

class RegionSeeder
{
    public function __construct(private PDO $pdo)
    {
    }

    public function run()
    {
        $regions = [
            ['Санкт-Петербург', 2],
            ['Уфа', 3],
            ['Нижний Новгород', 1],
            ['Владимир', 1],
            ['Кострома', 1],
            ['Екатеринбург', 4],
            ['Ковров', 1],
            ['Воронеж', 2],
            ['Самара', 3],
            ['Астрахань', 5]
        ];

        $sql = "INSERT INTO regions (name, travel_duration) VALUES (:name, :duration)";
        $stmt = $this->pdo->prepare($sql);

        foreach ($regions as $region) {
            $stmt->execute([
                ':name' => $region[0],
                ':duration' => $region[1]
            ]);
        }
    }
}