<?php

namespace Dots\Toko\Atk\Repository;

use Dots\Toko\Atk\Domain\User;

class UserRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): User {
        
        $statement = $this->connection->prepare("INSERT INTO users(id, name, password) values (?,?,?)");
        $statement->execute([
            $user->id, $user->name, $user->password
        ]);
        return $user;
    } 

    public function update(User $user): User
    {
        $statement = $this->connection->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
        $statement->execute([
            $user->name, $user->password, $user->id,
        ]);
        return $user;
    }

    public function findById(string $id): ?User {
        $statement = $this->connection->prepare("SELECT id, name, password FROM users WHERE id = ?");
        $statement->execute([$id]);

        try {
            
            if($row = $statement->fetch()){
                $user = new User();
                $user->id = $row['id'];
                $user->name = $row['name'];
                $user->password = $row['password'];
                return $user;
            }else {
                return null;
            }

        } finally {
            $statement->closeCursor();
        }
    }

    public function findAll(): array {
        $statement = $this->connection->prepare("SELECT id, name FROM users");
        $statement->execute();
    
        try {
            $users = [];
            while ($row = $statement->fetch()) {
                $user = new User();
                $user->id = $row['id'];
                $user->name = $row['name'];
                $users[] = $user; // Tambahkan user ke array
            }
            return $users; // Kembalikan array dari objek User
        } finally {
            $statement->closeCursor();
        }
    }
    

    public function deleteAll():void {

        $this->connection->exec("DELETE FROM users");

    }

}