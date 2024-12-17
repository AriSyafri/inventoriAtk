<?php

namespace Dots\Toko\Atk\Controller;

use Dots\Toko\Atk\App\View;
use Dots\Toko\Atk\Config\Database;
use Dots\Toko\Atk\Repository\SessionRepository;
use Dots\Toko\Atk\Repository\UserRepository;
use Dots\Toko\Atk\Service\SessionService;

class HomeController
{

    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);

    }


    function index(){
        $user = $this->sessionService->current();
        if ($user == null) {
            View::render('Home/index', [
                "title" => "Inventori ATK"
            ]);
        } else {
            View::render('Home/dashboard', [
                "title" => "Dashboard",
                "user" => [
                    "name" => $user->name
                ]
            ]);
        }
    }

}