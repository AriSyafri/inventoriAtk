<?php

namespace Dots\Toko\Atk\Repository;

use Dots\Toko\Atk\Config\Database;
use Dots\Toko\Atk\Domain\User;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionRepository->deleteAll();
        
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();
    }

    public function testSaveSuccess()
    {
        $user = new User();
        $user->id = "ari";
        $user->name = "Ari";
        $user->password = "rahasia";

        $this->userRepository->save($user);
        
        $result = $this->userRepository->findById($user->id);
        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->password, $result->password);
        
    }

    public function testFindByIdNotFound()
    {
        $user = $this->userRepository->findById("notFound");
        self::assertNull($user);
    }

    public function testUpdate()
    {
        $user = new User();
        $user->id = "ari";
        $user->name = "Ari";
        $user->password = "rahasia";

        $this->userRepository->save($user);

        $user->name = "tes";
        $this->userRepository->update($user);

        $result = $this->userRepository->findById($user->id);

        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->password, $result->password);
    }

}