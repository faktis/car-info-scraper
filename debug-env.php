<?php

header('Content-Type: text/plain');

echo 'MYSQLHOST set: '
    . (getenv('MYSQLHOST') ? 'yes' : 'no')
    . PHP_EOL;

echo 'MYSQLPORT set: '
    . (getenv('MYSQLPORT') ? 'yes' : 'no')
    . PHP_EOL;

echo 'MYSQLUSER set: '
    . (getenv('MYSQLUSER') ? 'yes' : 'no')
    . PHP_EOL;

echo 'MYSQLPASSWORD set: '
    . (getenv('MYSQLPASSWORD') ? 'yes' : 'no')
    . PHP_EOL;

echo 'MYSQLDATABASE set: '
    . (getenv('MYSQLDATABASE') ? 'yes' : 'no')
    . PHP_EOL;


    <?php

require_once __DIR__ . '/src/Database.php';

header('Content-Type: text/plain');

try {
    $database = new Database();

    $connection = $database->getConnection();

    echo "Database connection successful\n";

    $statement = $connection->query(
        'SELECT COUNT(*) AS total FROM cars'
    );

    $result = $statement->fetch(
        PDO::FETCH_ASSOC
    );

    echo 'Cars in database: '
        . $result['total']
        . PHP_EOL;

} catch (Throwable $exception) {
    http_response_code(500);

    echo 'Database connection failed'
        . PHP_EOL;

    echo $exception->getMessage();
}