<?php

require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/CarRepository.php';

header('Content-Type: application/json');

$make = $_GET['make'] ?? null;

$modelYear = isset($_GET['model_year']) && $_GET['model_year'] !== ''
    ? (int) $_GET['model_year']
    : null;

$registrationNumber = $_GET['registration_number'] ?? null;

$database = new Database();

$repository = new CarRepository(
    $database->getConnection()
);

$cars = $repository->search(
    $make,
    $modelYear,
    $registrationNumber
);

echo json_encode($cars);