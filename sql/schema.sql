CREATE TABLE cars (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    make VARCHAR(100) NOT NULL,
    model VARCHAR(150) NOT NULL,
    model_year SMALLINT UNSIGNED NULL,
    registration_number VARCHAR(20) NULL,
    source_url VARCHAR(500) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_cars_source_url (source_url)
);