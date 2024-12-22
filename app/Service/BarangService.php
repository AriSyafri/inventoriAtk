<?php

namespace Dots\Toko\Atk\Service;

use Dots\Toko\Atk\Config\Database;
use Dots\Toko\Atk\Domain\Barang;
use Dots\Toko\Atk\Exception\ValidationException;
use Dots\Toko\Atk\Model\BarangAddRequest;
use Dots\Toko\Atk\Model\BarangAddResponse;
use Dots\Toko\Atk\Model\BarangUpdateRequest;
use Dots\Toko\Atk\Model\BarangUpdateResponse;
use Dots\Toko\Atk\Repository\BarangRepository;

class BarangService
{

    private BarangRepository $barangRepository;

    public function __construct(BarangRepository $barangRepository)
    {
        $this->barangRepository = $barangRepository;
    }


    public function findAllItem(): array
    {
        try {
            // Panggil method findAll dari repository untuk mendapatkan semua user
            $users = $this->barangRepository->findAll();

            // Periksa apakah ada data pengguna
            if (empty($users)) {
                throw new ValidationException("No users found.");
            }

            return $users;

        } catch (\Exception $exception) {
            // Tangani exception jika terjadi kesalahan
            throw $exception;
        }
    }

    public function add(BarangAddRequest $request):BarangAddResponse
    {
        $this->validateAddItemRequest($request);

        try {
            Database::beginTransaction();
            $barang = $this->barangRepository->findById($request->id);
            if($barang != null){
                throw new ValidationException("Id already exists");
            }
    
            $brg = new Barang();
            $brg->id = $request->id;
            $brg->nama = $request->nama;
            $brg->brand = $request->brand;
            $brg->stok = $request->stok;
            $brg->harga = $request->harga;
            $brg->idUser = $request->idUser;
    
            $this->barangRepository->save($brg);
    
            $response = new BarangAddResponse();
            $response->barang = $brg;
            Database::commitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }  
    }

    private function validateAddItemRequest(BarangAddRequest $request){
        if($request->id == null || $request->nama == null || $request->brand == null ||
        $request->stok == null || $request->harga == null || $request->idUser == null ||
        trim($request->id) == "" || trim($request->nama) == "" ||
        trim($request->brand) == "" || trim($request->stok) == "" ||
        trim($request->harga) == "" ||trim($request->idUser) == "") {
            throw new ValidationException("id, name, stok, harga tidak boleh kosong");
        }
    }

    public function updateItem(BarangUpdateRequest $request): BarangUpdateResponse
    {

        $this->validateUpdateItemRequest($request);

        try {
            Database::beginTransaction();
            $barang = $this->barangRepository->findById($request->id);
            if($barang == null){
                throw new ValidationException("item notFound");
            }

            $barang->id = $request->id;
            $barang->nama = $request->nama;
            $barang->brand = $request->brand;
            $barang->stok = $request->stok;
            $barang->harga = $request->harga;
            $barang->idUser = $request->idUser;
            $this->barangRepository->update($barang);

            Database::commitTransaction();

            $response = new BarangUpdateResponse();
            $response->barang = $barang;
            return $response;

        } catch(\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }

    }

    private function validateUpdateItemRequest(BarangUpdateRequest $request){
        if($request->id == null || $request->nama == null || $request->brand == null ||
        $request->stok == null || $request->harga == null || $request->idUser == null ||
        trim($request->id) == "" || trim($request->nama) == "" ||
        trim($request->brand) == "" || trim($request->stok) == "" ||
        trim($request->harga) == "" ||trim($request->idUser) == "") {
            throw new ValidationException("id, name, stok, harga tidak boleh kosong");
        }
    }

    public function findBarangById(string $id): ?Barang
    {
        $barang = $this->barangRepository->findById($id);
        if (!$barang) {
            throw new ValidationException("Barang not found");
        }
        return $barang;
    }

    public function deleteBarang(string $id): void
    {
        $barang = $this->barangRepository->findById($id);
        if (!$barang) {
            throw new ValidationException("Barang not found");
        }
        $this->barangRepository->deleteById($id);
    }

    public function searchBarang(string $keyword): array
    {
        return $this->barangRepository->search($keyword);

    }


}
