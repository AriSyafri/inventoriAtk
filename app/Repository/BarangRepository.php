<?php

namespace Dots\Toko\Atk\Repository;

use Dots\Toko\Atk\Domain\Barang;

class BarangRepository
{

    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Barang $barang): barang {
        
        $statement = $this->connection->prepare("INSERT INTO barang(idbarang, namabarang, brand, stok, harga, id_user) values (?,?,?,?,?,?)");
        $statement->execute([
            $barang->id, $barang->nama, $barang->brand, $barang->stok, $barang->harga, $barang->idUser
        ]);
        return $barang;
    } 

    public function findById(string $id): ?Barang {
        $statement = $this->connection->prepare("SELECT idbarang, namabarang, brand, stok, harga, id_user FROM barang WHERE idbarang = ?");
        $statement->execute([$id]);

        try {
            
            if($row = $statement->fetch()){
                $brg = new Barang();
                $brg->id = $row['idbarang'];
                $brg->nama = $row['namabarang'];
                $brg->brand = $row['brand'];
                $brg->stok = $row['stok'];
                $brg->harga = $row['harga'];
                $brg->idUser = $row['id_user'];
                return $brg;
            }else {
                return null;
            }

        } finally {
            $statement->closeCursor();
        }
    }

    public function findAll(): array {
        $statement = $this->connection->prepare("SELECT idbarang, namabarang, brand, stok, harga, id_user FROM barang");
        $statement->execute();
    
        try {
            $barang = [];
            while ($row = $statement->fetch()) {
                $brg = new Barang();
                $brg->id = $row['idbarang'];
                $brg->nama = $row['namabarang'];
                $brg->brand = $row['brand'];
                $brg->stok = $row['stok'];
                $brg->harga = $row['harga'];
                $brg->idUser = $row['id_user'];
                $barang[] = $brg; // Tambahkan user ke array
            }
            return $barang; // Kembalikan array dari objek User
        } finally {
            $statement->closeCursor();
        }
    }

    public function update(Barang $barang): Barang
    {
        $statement = $this->connection->prepare("UPDATE barang SET namabarang = ?, brand = ?, stok = ?, harga = ?, id_user = ? WHERE idbarang = ?");
        $statement->execute([
            $barang->nama, $barang->brand, $barang->stok, $barang->harga, $barang->idUser, $barang->id
        ]);
        return $barang;
    }

    public function deleteById(string $id): void
    {
        $statement = $this->connection->prepare("DELETE FROM barang WHERE idbarang = ?");
        $statement->execute([$id]);
    }




}