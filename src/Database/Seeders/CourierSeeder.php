<?php

namespace App\Database\Seeders;

use PDO;

class CourierSeeder
{
    public function __construct(private PDO $pdo)
    {
    }

    public function run()
    {
        $couriers = [
            'Иванов Иван',
            'Петров Петр',
            'Сидоров Сидор',
            'Алексеев Алексей',
            'Николаев Николай',
            'Сергеев Сергей',
            'Андреев Андрей',
            'Дмитриев Дмитрий',
            'Александров Александр',
            'Павлов Павел'
        ];

        $sql = "INSERT INTO couriers (name) VALUES (:name)";
        $stmt = $this->pdo->prepare($sql);

        foreach ($couriers as $courier) {
            $stmt->execute([':name' => $courier]);
        }
    }
}