<?php

namespace Dots\Toko\Atk\App {
    function header(string $value) {
        echo $value;
    }
}

namespace Dots\Toko\Atk\Middleware {

    use Dots\Toko\Atk\Config\Database;
    use Dots\Toko\Atk\Domain\Session;
    use Dots\Toko\Atk\Domain\User;
    use Dots\Toko\Atk\Repository\SessionRepository;
    use Dots\Toko\Atk\Repository\UserRepository;
    use Dots\Toko\Atk\Service\SessionService;
    use PHPUnit\Framework\TestCase;
    
    class MustLoginMiddlewareTest extends TestCase
    {
        private MustLoginMiddleware $middleware;
        private UserRepository $userRepository;
        private SessionRepository $sessionRepository;
    
        protected function setUp():void
        {
            $this->middleware = new MustLoginMiddleware();
            putenv("mode=test");

            $this->userRepository = new UserRepository(Database::getConnection());
            $this->sessionRepository = new SessionRepository(Database::getConnection());

            $this->sessionRepository->deleteAll();
            $this->userRepository->deleteAll();

        }
    
        public function testBeforeGuest()
        {
            $this->middleware->before();
            
            $this->expectOutputRegex("[Location: /users/login]");
    
        }

        public function testBeforeLoginUser()
        {

            $user = new User();
            $user->id = "ari";
            $user->name = "ari";
            $user->password = "rahasia";
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $this->middleware->before();
            $this->expectOutputString("");
    
        }
    }
}
