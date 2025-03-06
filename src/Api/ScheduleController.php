<?php

use App\Database\Connection;

$pdo = Connection::get()->connect();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $date = $_GET['date'] ?? date('Y-m-d');
    $sql = "
        SELECT trips.*, couriers.name as courier_name, regions.name as region_name
        FROM trips
        JOIN couriers ON trips.courier_id = couriers.id
        JOIN regions ON trips.region_id = regions.id
        WHERE departure_date = :date
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':date' => $date]);
    $trips = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($trips);
    exit;
}

if ($method === 'POST') {
    if (empty($_POST['region_id']) || empty($_POST['courier_id']) || empty($_POST['departure_date'])) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Все поля обязательны для заполнения']);
        exit;
    }

    $region_id = $_POST['region_id'];
    $courier_id = $_POST['courier_id'];
    $departure_date = $_POST['departure_date'];

    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM trips 
        WHERE courier_id = :courier_id 
        AND :departure_date BETWEEN departure_date AND arrival_date
    ");
    $stmt->execute([
        ':courier_id' => $courier_id,
        ':departure_date' => $departure_date
    ]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Курьер уже занят на эту дату']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT travel_duration FROM regions WHERE id = :region_id");
    $stmt->execute([':region_id' => $region_id]);
    $region = $stmt->fetch(PDO::FETCH_ASSOC);

    $arrival_date = date('Y-m-d', strtotime($departure_date . " + {$region['travel_duration']} days"));

    $sql = "INSERT INTO trips (region_id, courier_id, departure_date, arrival_date) VALUES (:region_id, :courier_id, :departure_date, :arrival_date)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':region_id' => $region_id,
        ':courier_id' => $courier_id,
        ':departure_date' => $departure_date,
        ':arrival_date' => $arrival_date
    ]);

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Поездка успешно добавлена']);
    exit;
}

header('Content-Type: application/json');
http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);