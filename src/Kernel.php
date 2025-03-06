<?php

namespace App;

use App\Database\Connection;
use App\Database\Migrations\create_couriers;
use App\Database\Migrations\create_regions;
use App\Database\Migrations\create_trips;
use App\Database\Seeders\CourierSeeder;
use App\Database\Seeders\RegionSeeder;
use App\Database\Seeders\TripSeeder;
use PDO;

require_once "../src/Database/Connection.php";
require_once "../src/Database/Migrations/create_couriers.php";
require_once "../src/Database/Migrations/create_regions.php";
require_once "../src/Database/Migrations/create_trips.php";
require_once "../src/Database/Seeders/CourierSeeder.php";
require_once "../src/Database/Seeders/RegionSeeder.php";
require_once "../src/Database/Seeders/TripSeeder.php";

final class Kernel {
    private PDO $pdo;
    private array $map;

    private bool $withMigrations;

    private bool $withSeeders;

    public function __construct(array $map, bool $withMigrations = false, bool $withSeeders = false)
    {
        $this->map = $map;
        $this->withMigrations = $withMigrations;
        $this->withSeeders = $withSeeders;
    }

    public function start() {
        try {

            $this->fetchPdo();
            if ($this->withMigrations) $this->runMigrations();
            if ($this->withSeeders) $this->runSeeders();
            $this->fetchPage();

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
    private function fetchPdo() {
        $this->pdo = Connection::get()->connect();
    }
    private function runMigrations() {
        (new create_couriers($this->pdo))->up();
        (new create_regions($this->pdo))->up();
        (new create_trips($this->pdo))->up();
    }

    private function runSeeders() {
        (new CourierSeeder($this->pdo))->run();
        (new RegionSeeder($this->pdo))->run();
        (new TripSeeder($this->pdo))->run();
    }

    private function fetchPage()
    {
        include $this->map[$_SERVER['REQUEST_URI']];
    }
}