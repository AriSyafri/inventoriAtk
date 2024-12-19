<?php

namespace Dots\Toko\Atk\Service;

require_once __DIR__ . '/../Helper/helper.php';

use Dots\Toko\Atk\Config\Database;
use Dots\Toko\Atk\Domain\Session;
use Dots\Toko\Atk\Domain\User;
use Dots\Toko\Atk\Repository\SessionRepository;
use Dots\Toko\Atk\Repository\UserRepository;
use PHPUnit\Framework\TestCase;


class SessionServiceTest extends TestCase
{


    private SessionService $sessionService;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp():void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->id = "ari";
        $user->name = "Ari";
        $user->password = "rahasia";
        $this->userRepository->save($user);

    }

    public function testCreate()
    {
        $session = $this->sessionService->create("ari");

        $this->expectOutputRegex("[X-DOTS-SESSION: $session->id]");

        $result = $this->sessionRepository->findById($session->id);

        self::assertEquals("ari", $result->userId);
        
    }

    public function testDestroy()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = "ari";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->sessionService->destroy();

        $this->expectOutputRegex("[X-DOTS-SESSION: ]");

        $result = $this->sessionRepository->findById($session->id);
        self::assertNull($result);

    }

    public function testCurrent()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId = "ari";

        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $user = $this->sessionService->current();

        self::assertEquals($session->userId, $user->id);
    }

}