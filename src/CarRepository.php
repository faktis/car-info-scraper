<?php

require_once __DIR__ . '/Car.php';

class CarRepository
{
    public function __construct( private PDO $connection) {}

    public function insert( Car $car ): bool
    {
        $sql = "
            INSERT IGNORE INTO cars (
                make,
                model,
                model_year,
                registration_number,
                source_url
            )
            VALUES (
                :make,
                :model,
                :model_year,
                :registration_number,
                :source_url
            )
        ";

        $statement = $this->connection->prepare(
            $sql
        );

        $statement->execute([
            'make' => $car->make,
            'model' => $car->model,
            'model_year' => $car->modelYear,
            'registration_number' => $car->registrationNumber,
            'source_url' => $car->sourceUrl
        ]);

        return $statement->rowCount() > 0;
    }

    public function findById(int $id): ?array
    {
        $sql = "
            SELECT
                id,
                make,
                model,
                model_year,
                registration_number,
                source_url,
                created_at
            FROM cars
            WHERE id = :id
        ";

        $statement = $this->connection->prepare($sql);

        $statement->execute([
            'id' => $id
        ]);

        $car = $statement->fetch(PDO::FETCH_ASSOC);

        return $car === false ? null : $car;
    }

    public function findByMake(string $make): array
    {
        $sql = "
            SELECT
                id,
                make,
                model,
                model_year,
                registration_number,
                source_url,
                created_at
            FROM cars
            WHERE make = :make
            ORDER BY model_year DESC
        ";

        $statement = $this->connection->prepare($sql);

        $statement->execute([
            'make' => $make
        ]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll(): array
    {
        $sql = "
            SELECT
                id,
                make,
                model,
                model_year,
                registration_number,
                source_url,
                created_at
            FROM cars
            ORDER BY id DESC
        ";

        $statement = $this->connection->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(
        int $id,
        string $make,
        string $model,
        ?int $modelYear,
        ?string $registrationNumber,
        ?string $title = null,
        ?string $description = null,
        ?string $sourceUrl = null
    ): bool {
        $sql = "
            UPDATE cars
            SET
                make = :make,
                model = :model,
                model_year = :model_year,
                registration_number = :registration_number,
                source_url = :source_url
            WHERE id = :id
        ";

        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            'id' => $id,
            'make' => $make,
            'model' => $model,
            'model_year' => $modelYear,
            'registration_number' => $registrationNumber,
            'source_url' => $sourceUrl
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "
            DELETE FROM cars
            WHERE id = :id
        ";

        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            'id' => $id
        ]);
    }

    public function search(
    ?string $make,
    ?int $modelYear,
    ?string $registrationNumber
    ): array 
    {
        $sql = "
            SELECT
                id,
                make,
                model,
                model_year,
                registration_number,
                title,
                description,
                source_url
            FROM cars
            WHERE 1 = 1
        ";

        $parameters = [];

        if ($make !== null && $make !== '') {
            $sql .= " AND make = :make";
            $parameters['make'] = $make;
        }

        if ($modelYear !== null) {
            $sql .= " AND model_year = :model_year";
            $parameters['model_year'] = $modelYear;
        }

        if ($registrationNumber !== null && $registrationNumber !== '') {
            $sql .= " AND registration_number = :registration_number";
            $parameters['registration_number'] = $registrationNumber;
        }

        $sql .= " ORDER BY model_year DESC";

        $statement = $this->connection->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}