<?php

require_once __DIR__ . '/../vendor/autoload.php';


use Dots\Toko\Atk\App\Router;
use Dots\Toko\Atk\Controller\HomeController;


Router::add('GET', '/products/([0-9a-zA-Z]*)/categories/([0-9a-zA-Z]*)', ProductController::class, 'categories');

Router::add('GET', '/', HomeController::class, 'index', []);


Router::run();

