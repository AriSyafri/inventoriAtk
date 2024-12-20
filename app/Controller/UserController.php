<?php


namespace Dots\Toko\Atk\Controller;

use Dots\Toko\Atk\App\View;
use Dots\Toko\Atk\Config\Database;
use Dots\Toko\Atk\Exception\ValidationException;
use Dots\Toko\Atk\Model\UserLoginRequest;
use Dots\Toko\Atk\Model\UserPasswordUpdateRequest;
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

    public function updatePassword()
    {
        $user = $this->sessionService->current();
        View::render('User/password', [
            "title" => "Update user password",
            "user" => [
                "id" => $user->id
            ]
            ]);
    }

    public function postUpdatePassword()
    {
        $user = $this->sessionService->current();
        $request = new UserPasswordUpdateRequest();
        $request->id = $user->id;
        $request->oldPassword = $_POST['oldPassword'];
        $request->newPassword = $_POST['newPassword'];

        try {
            $this->userService->updatePassword($request);
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('User/password', [
                "title" => "Update user password",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $user->id
                ]
                ]);
        }
    }

    public function getAllUsers()
    {
        try {
            // Memanggil service untuk mendapatkan semua data pengguna
            $users = $this->userService->findAllUsers();

            // Mengarahkan ke tampilan yang menampilkan daftar pengguna
            View::render('User/show', [
                'title' => 'Show Data',
                'users' => $users // Mengirim array users ke view
            ]);
        } catch (ValidationException $exception) {
            // Menangani jika tidak ada pengguna ditemukan atau error validasi lainnya
            View::render('User/show', [
                'title' => 'Show Data',
                'error' => $exception->getMessage(),
                'users' => [] // Mengirim array kosong ke view
            ]);
        }
    }

    public function deleteUser()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            try {
                $this->userService->deleteUserById($id);
                View::redirect('/users/show');
            } catch (ValidationException $exception) {
                View::render('User/show', [
                    'title' => 'Show Data',
                    'error' => $exception->getMessage(),
                    'users' => $this->userService->findAllUsers()
                ]);
            }
        } else {
            View::redirect('/users/show');
        }
    }
    public function editUser()
    {
        $id = $_GET['id'] ?? null;
    
        if (!$id) {
            View::redirect('/users'); // Redirect jika ID tidak ditemukan
        }
    
        $user = $this->userService->findUserById($id);
    
        if (!$user) {
            View::redirect('/users'); // Redirect jika user tidak ditemukan
        }
    
        View::render('User/edit', [
            'title' => 'Edit User',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
        ]);
    }
    
    public function postEditUser()
    {
        $request = new UserProfileUpdateRequest();
        $request->id = $_POST['id'];
        $request->name = $_POST['name'];
    
        try {
            $this->userService->updateProfile($request);
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::render('User/edit', [
                'title' => 'Edit User',
                'error' => $exception->getMessage(),
                'user' => [
                    'id' => $request->id,
                    'name' => $request->name,
                ],
            ]);
        }
    }
    

    



}