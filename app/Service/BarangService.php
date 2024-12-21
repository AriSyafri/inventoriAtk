<?php

namespace Dots\Toko\Atk\Service;

use Dots\Toko\Atk\Exception\ValidationException;
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

}
