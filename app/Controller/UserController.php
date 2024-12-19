<?php


namespace Dots\Toko\Atk\Controller;

use Dots\Toko\Atk\App\View;
use Dots\Toko\Atk\Config\Database;
use Dots\Toko\Atk\Exception\ValidationException;
use Dots\Toko\Atk\Model\UserLoginRequest;
use Dots\Toko\Atk\Model\UserProfileUpdateRequest;
use Dots\Toko\Atk\Model\UserRegisterRequest;
use Dots\Toko\Atk\Repository\SessionRepository;
use Dots\Toko\Atk\Repository\UserRepository;
use Dots\Toko\Atk\Service\SessionService;
use Dots\Toko\Atk\Service\UserService;

class UserController
{

    private UserService $userService;

    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);

        $sesionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sesionRepository, $userRepository);
    } 

    
    public function register(){
        View::render('User/register', [
            'title' => 'Register new user'
        ]);
    }

    public function postRegister(){
        $request = new UserRegisterRequest();
        $request->id = $_POST['id'];
        $request->name = $_POST['name'];
        $request->password = $_POST['password'];

        try {
            $this->userService->register($request);
            // redirect to users/login
            View::redirect('/users/login');
        } catch (ValidationException $exception) {
            View::render('User/register', [
                'title' => 'Register new user',
                'error' => $exception->getMessage()
            ]);
        }


    }

    public function login()
    {
        View::render('User/login', [
            "title" =>"Login user"
        ]);
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->id = $_POST['id'];
        $request->password = $_POST['password'];

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->id);

            View::redirect('/');
        } catch (ValidationException $exception){
            View::render('User/login', [
                'title' => 'Login user',
                'error' => $exception->getMessage()
            ]);
        }

    }

    public function logout(){
        $this->sessionService->destroy();
        View::redirect("/");
    }

    public function updateProfile(){
        
        $user = $this->sessionService->current();

        View::render('User/profile', [
            "title" => "Update user Profile",
            "user" => [
                "id" => $user->id,
                "name" => $user->name 
            ]
        ]);       
    }

    public function postUpdateProfile()
    {
        $user = $this->sessionService->current();

        $request = new UserProfileUpdateRequest();
        $request->id = $user->id;
        $request->name = $_POST['name'];

        try {
            $this->userService->updateProfile($request);
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('User/profile', [
                'title' => 'Update user Profile',
                'error' => $exception->getMessage(),
                "user" => [
                    "id" => $user->id,
                    "name" => $user->name 
                ]
            ]);
        }
    }
}