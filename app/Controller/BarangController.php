<?php

namespace Dots\Toko\Atk\Controller;

use Dots\Toko\Atk\App\View;
use Dots\Toko\Atk\Config\Database;
use Dots\Toko\Atk\Exception\ValidationException;
use Dots\Toko\Atk\Repository\BarangRepository;
use Dots\Toko\Atk\Repository\SessionRepository;
use Dots\Toko\Atk\Service\BarangService;
use Dots\Toko\Atk\Service\SessionService;

Class BarangController {

    private BarangService $barangService;

    //private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $barangRepository = new BarangRepository($connection);
        $this->barangService = new BarangService($barangRepository);

        // $sesionRepository = new SessionRepository($connection);
        // $this->sessionService = new SessionService($sesionRepository, $barangRepository);
    } 

    public function getAllBarang()
    {
        try {
            // Memanggil service untuk mendapatkan semua data pengguna
            $barang = $this->barangService->findAllItem();

            // Mengarahkan ke tampilan yang menampilkan daftar pengguna
            View::render('Barang/show', [
                'title' => 'Show Item',
                'barang' => $barang // Mengirim array users ke view
            ]);
        } catch (ValidationException $exception) {
            // Menangani jika tidak ada pengguna ditemukan atau error validasi lainnya
            View::render('Barang/show', [
                'title' => 'Show Item',
                'error' => $exception->getMessage(),
                'barang' => [] // Mengirim array kosong ke view
            ]);
        }
    }
}