<?php

namespace Dots\Toko\Atk\Controller;

use Dots\Toko\Atk\Config\Database;
use Dots\Toko\Atk\Domain\Session;
use Dots\Toko\Atk\Domain\User;
use Dots\Toko\Atk\Repository\SessionRepository;
use Dots\Toko\Atk\Repository\UserRepository;
use Dots\Toko\Atk\Service\SessionService;
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{

    private HomeController $homeController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    public function setUp():void 
    {
        $this->homeController = new HomeController();
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testGuest()
    {
        $this->homeController->index();

        $this->expectOutputRegex("[Pengelolaan Inventori Toko Atk]");
    }

    public function testUserLogin()
    {
        $user = new User();
        $user->id = "ari";
        $user->name = "Ari";
        $user->password = "rahasia";
        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->userId = $user->id;
        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->homeController->index();

        $this->expectOutputRegex("[Hello Ari]");
    }
}