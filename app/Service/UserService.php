<?php

namespace Dots\Toko\Atk\Service;

use Dots\Toko\Atk\Config\Database;
use Dots\Toko\Atk\Domain\User;
use Dots\Toko\Atk\Exception\ValidationException;
use Dots\Toko\Atk\Model\UserLoginRequest;
use Dots\Toko\Atk\Model\UserLoginResponse;
use Dots\Toko\Atk\Model\UserPasswordUpdateRequest;
use Dots\Toko\Atk\Model\UserPasswordUpdateResponse;
use Dots\Toko\Atk\Model\UserProfileUpdateRequest;
use Dots\Toko\Atk\Model\UserProfileUpdateResponse;
use Dots\Toko\Atk\Model\UserRegisterRequest;
use Dots\Toko\Atk\Model\UserRegisterResponse;
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
                throw new ValidationException("User id already exists");
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
            throw new ValidationException("id, name, password can not blank");
        }
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validateUserLoginRequest($request);

        $user = $this->userRepository->findById($request->id);
        if($user == null){
            throw new ValidationException("Id or password is wrong");
        }

        if(password_verify($request->password, $user->password)) {
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;
        } else {
            throw new ValidationException("Id or password is wrong");
        }

    }

    private function validateUserLoginRequest(UserLoginRequest $request){
        if($request->id == null || $request->password == null ||
        trim($request->id) == "" || trim($request->password) == "") {
            throw new ValidationException("id, password can not blank");
        }
    }

    public function updateProfile(UserProfileUpdateRequest $request): UserProfileUpdateResponse
    {

        $this->validateUserProfileUpdateRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if($user == null) {
                throw new ValidationException("User is not found");
            }

            $user->name = $request->name;
            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UserProfileUpdateResponse();
            $response->user = $user;
            return $response;

        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateUserProfileUpdateRequest(UserProfileUpdateRequest $request){
        if($request->id == null || $request->name == null ||
        trim($request->id) == "" || trim($request->name) == "") {
            throw new ValidationException("id, Name can not blank");
        }
    }

    public function updatePassword(UserPasswordUpdateRequest $request): UserPasswordUpdateResponse
    {
        $this->validateUserPassworUpdateRequest($request);

        try {
            Database::beginTransaction();
            
            $user = $this->userRepository->findById($request->id);

            if($user == null){
                throw new ValidationException("User is not found");
            }

            if(!password_verify($request->oldPassword, $user->password)){
                throw new ValidationException("Old Password is wrong");
            }

            $user->password = password_hash($request->newPassword, PASSWORD_BCRYPT);
            $this->userRepository->update($user);
            Database::commitTransaction();

            $response = new UserPasswordUpdateResponse();
            $response->user = $user;
            return $response;
            
        } catch(\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateUserPassworUpdateRequest(UserPasswordUpdateRequest $request)
    {
        if($request->id == null || $request->oldPassword == null || $request->newPassword == null ||
        trim($request->id) == "" || trim($request->oldPassword) == "" || trim($request->newPassword) == "") {
            throw new ValidationException("id, Old password, New password can not blank");
        }
    }

}