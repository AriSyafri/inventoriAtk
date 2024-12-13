<?php

function getDatabaseConfig(): array {
    return [
        "database" => [
            "test" => [
                "url" => "mysql:host=localhost:3306;dbname=inventory_atk_test",
                "username" => "root",
                "password" => ""
            ],
            "prod" => [
                "url" => "mysql:host=localhost:3306;dbname=inventory_atk",
                "username" => "root",
                "password" => ""
            ],        
        ]
    ];
}