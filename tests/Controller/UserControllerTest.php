<?php

namespace Dots\Toko\Atk\App {
    function header(string $value){
        echo $value;
    }
}

namespace Dots\Toko\Atk\Controller {

    use Dots\Toko\Atk\Config\Database;
    use Dots\Toko\Atk\Domain\User;
    use Dots\Toko\Atk\Repository\UserRepository;
    use PHPUnit\Framework\TestCase;
    
    class UserControllerTest extends TestCase
    {
    
        private UserController $userController;
        private UserRepository $userRepository;
        
        protected function setUp(): void
        {
            $this->userController = new UserController();
    
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
    }
}