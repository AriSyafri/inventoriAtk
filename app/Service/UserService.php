<?php

namespace Dots\Toko\Atk\Service;

use Dots\Toko\Atk\Config\Database;
use Dots\Toko\Atk\Domain\User;
use Dots\Toko\Atk\Exception\ValidationException;
use Dots\toko\Atk\Model\UserRegisterRequest;
use Dots\toko\Atk\Model\UserRegisterResponse;
use Dots\Toko\Atk\Repository\UserRepository;


class UserService
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(UserRegisterRequest $request):UserRegisterResponse
    {
        $this->validateUserRegistrationRequest($request);

        try {
            Database::beginTransaction();
            $user = $this->userRepository->findById($request->id);
            if($user != null){
                throw new ValidationException("User id alreadt exists");
            }
    
            $user = new User();
            $user->id = $request->id;
            $user->name = $request->name;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);
    
            $this->userRepository->save($user);
    
            $response = new UserRegisterResponse();
            $response->user = $user;
            Database::commitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }   
    } 

    private function validateUserRegistrationRequest(UserRegisterRequest $request){
        if($request->id == null || $request->name == null || $request->password == null ||
        trim($request->id) == "" || trim($request->name) == "" ||
        trim($request->password) == "") {
            throw new ValidationException("id, name, password ca not blank");
        }
    }

    

}