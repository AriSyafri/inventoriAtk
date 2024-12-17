<?php

require_once __DIR__ . '/../vendor/autoload.php';


use Dots\Toko\Atk\App\Router;
use Dots\Toko\Atk\Controller\HomeController;
use Dots\Toko\Atk\Controller\UserController;
use Dots\Toko\Atk\Config\Database;

Database::getConnection('prod');


// home controller
Router::add('GET', '/', HomeController::class, 'index', []);

// user controller
Router::add('GET', '/users/register', UserController::class, 'register', []);
Router::add('POST', '/users/register', UserController::class, 'postRegister', []);
Router::add('GET', '/users/login', UserController::class, 'login', []);
Router::add('POST', '/users/login', UserController::class, 'postLogin', []);
Router::add('GET', '/users/logout', UserController::class, 'logout', []);


Router::run();

