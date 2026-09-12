<?php

require_once __DIR__ . '/Car.php';

class CarRepository
{
    public function __construct( private PDO $connection) {}

    public function insert( Car $car ): bool
    {
        $sql = "
            INSERT IGNORE INTO cars ( make, model, model_year, registration_number, source_url )
            VALUES ( :make, :model, :model_year, :registration_number, :source_url )";

        $statement = $this->connection->prepare( $sql );

        $statement->execute(
        [
            'make' => $car->make,
            'model' => $car->model,
            'model_year' => $car->modelYear,
            'registration_number' => $car->registrationNumber,
            'source_url' => $car->sourceUrl
        ]);

        return $statement->rowCount() > 0;
    }

    public function search( ?string $make, ?int $modelYear, ?string $registrationNumber ): array 
    {
        $sql = "
            SELECT id, make, model, model_year, registration_number, source_url
            FROM cars
            WHERE 1 = 1 ";

        $parameters = [];

        if( $make !== null && $make !== '' ) 
        {
            $sql .= " AND make = :make";
            $parameters['make'] = $make;
        }

        if( $modelYear !== null ) 
        {
            $sql .= " AND model_year = :model_year";
            $parameters['model_year'] = $modelYear;
        }

        if( $registrationNumber !== null && $registrationNumber !== '' ) 
        {
            $sql .= " AND registration_number = :registration_number";
            $parameters['registration_number'] = $registrationNumber;
        }

        $statement = $this->connection->prepare( $sql );
        $statement->execute( $parameters );

        return $statement->fetchAll( PDO::FETCH_ASSOC );
    }
}