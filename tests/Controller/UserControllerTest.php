<?php

namespace Dots\Toko\Atk\Controller {
    require_once __DIR__ . '/../Helper/helper.php';
    
    use Dots\Toko\Atk\Config\Database;
    use Dots\Toko\Atk\Domain\Session;
    use Dots\Toko\Atk\Domain\User;
    use Dots\Toko\Atk\Repository\SessionRepository;
    use Dots\Toko\Atk\Repository\UserRepository;
    use Dots\Toko\Atk\Service\SessionService;
    use PHPUnit\Framework\TestCase;
    
    class UserControllerTest extends TestCase
    {
    
        private UserController $userController;
        private UserRepository $userRepository;
        private SessionRepository $sessionRepository;
        
        protected function setUp(): void
        {
            $this->userController = new UserController();

            $this->sessionRepository = new SessionRepository(Database::getConnection());
            $this->sessionRepository->deleteAll();
    
            $this->userRepository = new UserRepository(Database::getConnection());
            $this->userRepository->deleteAll();

            putenv("mode=test");
        }
    
        public function testRegister()
        {
            $this->userController->register();
    
            $this->expectOutputRegex("[Register]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Register new user]");
        }
    
        public function testPostRegisterSucces()
        {
            $_POST['id'] = "ari";
            $_POST['name'] = "ari";
            $_POST['password'] = "ari";
    
            $this->userController->postRegister();
    
            $this->expectOutputRegex("[Location: /users/login]");
    
        }
    
        public function testPostRegisterValidationError()
        {
            $_POST['id'] = "";
            $_POST['name'] = "";
            $_POST['password'] = "";
    
            $this->userController->postRegister();
    
            $this->expectOutputRegex("[Register]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[id, name, password can not blank]");
    
        }
    
        public function testPostRegisterDuplicate()
        {
            $user = new User();
            $user->id = "aari";
            $user->name = "aari";
            $user->password = "aari";
    
            $this->userRepository->save($user);
    
            $_POST['id'] = "aari";
            $_POST['name'] = "aari";
            $_POST['password'] = "aari";
    
            $this->userController->postRegister();
    
            $this->expectOutputRegex("[Register]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[User id already exists]");
        }

        public function testLogin()
        {
            $this->userController->login();

            $this->expectOutputRegex("[Login User]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
        }

        public function testLoginSuccess()
        {

            $user = new User();
            $user->id = "aari";
            $user->name = "aari";
            $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
    
            $this->userRepository->save($user);

            $_POST['id'] = "aari";
            $_POST['password'] = 'rahasia';

            $this->userController->postLogin();

            $this->expectOutputRegex("[Location: /]");
            $this->expectOutputRegex("[X-DOTS-SESSION: ]");
            

        }

        public function testLoginValidationError()
        {
            $_POST['id'] = '';
            $_POST['password'] = '';

            $this->userController->postLogin();

            $this->expectOutputRegex("[Login User]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[id, password can not blank]");

        }

        public function testLoginUserNotFound()
        {
            $_POST['id'] = 'notFound';
            $_POST['password'] = 'notFound';

            $this->userController->postLogin();

            $this->expectOutputRegex("[Login User]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Id or password is wrong]");
        }

        public function testLoginWrongPassword()
        {
            $user = new User();
            $user->id = "aari";
            $user->name = "aari";
            $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
    
            $this->userRepository->save($user);

            $_POST['id'] = "aari";
            $_POST['password'] = 'salah';

            $this->userController->postLogin();

            $this->expectOutputRegex("[Login User]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Id or password is wrong]");
        }

        public function testLogout()
        {
            $user = new User();
            $user->id = "aari";
            $user->name = "aari";
            $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
    
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $this->userController->logout();

             $this->expectOutputRegex("[Location: /]");
             $this->expectOutputRegex("[X-DOTS-SESSION: ]");


        }

        public function testUpdateProfile()
        {
            $user = new User();
            $user->id = "aari";
            $user->name = "Aari";
            $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
    
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $this->userController->updateProfile();

            $this->expectOutputRegex("[Profile]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[Aari]");
            $this->expectOutputRegex("[aari]");

        }

        public function testPostUpdateProfileSuccess()
        {

            $user = new User();
            $user->id = "aari";
            $user->name = "aari";
            $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
    
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $_POST['name'] = 'Budi';
            $this->userController->postUpdateProfile();

            $this->expectOutputRegex("[Location: /]");

            $result = $this->userRepository->findById("aari");
            self::assertEquals("Budi", $result->name);

        }

        public function testPostUpdateValidationError()
        {
            $user = new User();
            $user->id = "aari";
            $user->name = "aari";
            $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
    
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $_POST['name'] = '';
            $this->userController->postUpdateProfile();
            
            $this->expectOutputRegex("[Profile]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[Name]");
            $this->expectOutputRegex("[aari]");
            $this->expectOutputRegex("[id, Name can not blank]");
        }

        public function testUpdatePassword()
        {
            $user = new User();
            $user->id = "aari";
            $user->name = "Aari";
            $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
    
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $this->userController->updatePassword();

            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[aari]");


        }

        public function testUpdatePasswordSuccess()
        {
            $user = new User();
            $user->id = "aari";
            $user->name = "aari";
            $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $_POST['oldPassword'] = 'rahasia';
            $_POST['newPassword'] = 'budi';

            $this->userController->postUpdatePassword();
            $this->expectOutputRegex("[Location: /]");

            $result = $this->userRepository->findById($user->id);
            self::assertTrue(password_verify("budi", $result->password));

        }

        public function testPostUpdatePasswordValidationError()
        {
            $user = new User();
            $user->id = "aari";
            $user->name = "aari";
            $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
    
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $_POST['oldPassword'] = '';
            $_POST['newPassword'] = '';
            
            $this->userController->postUpdatePassword();

            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[aari]");
            $this->expectOutputRegex("[id, Old password, New password can not blank]");
        }

        public function testPostUpdatePasswordWrongOldPassword()
        {
            $user = new User();
            $user->id = "aari";
            $user->name = "aari";
            $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
    
            $this->userRepository->save($user);

            $session = new Session();
            $session->id = uniqid();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $_POST['oldPassword'] = 'salah';
            $_POST['newPassword'] = 'budi';
            
            $this->userController->postUpdatePassword();

            $this->expectOutputRegex("[Password]");
            $this->expectOutputRegex("[Id]");
            $this->expectOutputRegex("[aari]");
            $this->expectOutputRegex("[Old Password is wrong]");
        }

    }
    
}