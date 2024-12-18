<?php

require_once __DIR__ . '/../vendor/autoload.php';


use Dots\Toko\Atk\App\Router;
use Dots\Toko\Atk\Controller\HomeController;
use Dots\Toko\Atk\Controller\UserController;
use Dots\Toko\Atk\Config\Database;
use Dots\Toko\Atk\Middleware\MustLoginMiddleware;
use Dots\Toko\Atk\Middleware\MustNotLoginMiddleware;

Database::getConnection('prod');


// home controller
Router::add('GET', '/', HomeController::class, 'index', []);

// user controller
Router::add('GET', '/users/register', UserController::class, 'register', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/register', UserController::class, 'postRegister', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/login', UserController::class, 'login', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);


Router::run();

