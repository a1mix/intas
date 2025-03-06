<?php

namespace App\Database\Seeders;

use PDO;

class TripSeeder
{
    public function __construct(private PDO $pdo)
    {
    }

    public function run()
    {
        // Получаем все ID курьеров и регионов
        $couriers = $this->pdo->query("SELECT id FROM couriers")->fetchAll(PDO::FETCH_COLUMN);
        $regions = $this->pdo->query("SELECT id, travel_duration FROM regions")->fetchAll(PDO::FETCH_ASSOC);

        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime('+3 months'));

        $sql = "INSERT INTO trips (region_id, courier_id, departure_date, arrival_date) VALUES (:region_id, :courier_id, :departure_date, :arrival_date)";
        $stmt = $this->pdo->prepare($sql);

        $current_date = $start_date;
        while ($current_date <= $end_date) {
            foreach ($couriers as $courier_id) {
                $region = $regions[array_rand($regions)];
                $arrival_date = date('Y-m-d', strtotime($current_date . " + {$region['travel_duration']} days"));

                $stmt->execute([
                    ':region_id' => $region['id'],
                    ':courier_id' => $courier_id,
                    ':departure_date' => $current_date,
                    ':arrival_date' => $arrival_date
                ]);
            }
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }
    }
}