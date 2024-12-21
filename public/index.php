<?php

require_once __DIR__ . '/../vendor/autoload.php';


use Dots\Toko\Atk\App\Router;
use Dots\Toko\Atk\Controller\HomeController;
use Dots\Toko\Atk\Controller\UserController;

use Dots\Toko\Atk\Config\Database;
use Dots\Toko\Atk\Controller\BarangController;
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
Router::add('GET', '/users/profile', UserController::class, 'updateProfile', [MustLoginMiddleware::class]);
Router::add('POST', '/users/profile', UserController::class, 'postUpdateProfile', [MustLoginMiddleware::class]);
Router::add('GET', '/users/password', UserController::class, 'updatePassword', [MustLoginMiddleware::class]);
Router::add('POST', '/users/password', UserController::class, 'postUpdatePassword', [MustLoginMiddleware::class]);
Router::add('GET', '/users/show', UserController::class, 'getAllUsers', [MustLoginMiddleware::class]);
Router::add('GET', '/users/delete', UserController::class, 'deleteUser', [MustLoginMiddleware::class]);
Router::add('GET', '/users/edit', UserController::class, 'editUser', [MustLoginMiddleware::class]);
Router::add('POST', '/users/edit', UserController::class, 'postEditUser', [MustLoginMiddleware::class]);
Router::add('GET', '/users/list', UserController::class, 'getAllUsersExceptCurrent', [MustLoginMiddleware::class]);
Router::add('GET', '/barang/show', BarangController::class, 'getAllBarang', [MustLoginMiddleware::class]);
Router::add('GET', '/users/search', UserController::class, 'search', [MustLoginMiddleware::class]);



Router::run();

