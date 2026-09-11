<?php

class Car
{
    public function __construct(
        public string $make,
        public string $model,
        public ?int $modelYear,
        public ?string $registrationNumber,
        public ?string $title,
        public ?string $description,
        public string $sourceUrl
    ) {}
}