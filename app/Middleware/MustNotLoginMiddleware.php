<?php

namespace Dots\Toko\Atk\Middleware;

use Dots\Toko\Atk\App\View;
use Dots\Toko\Atk\Config\Database;
use Dots\Toko\Atk\Repository\SessionRepository;
use Dots\Toko\Atk\Repository\UserRepository;
use Dots\Toko\Atk\Service\SessionService;

class MustNotLoginMiddleware implements Middleware
{

    private SessionService $sessionService;

    public function __construct()
    {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function before(): void
    {
        $user = $this->sessionService->current();
        if ($user != null) {
            View::redirect('/');
        }
    }
}