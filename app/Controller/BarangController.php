<?php

namespace Dots\Toko\Atk\Controller;

use Dots\Toko\Atk\App\View;
use Dots\Toko\Atk\Config\Database;
use Dots\Toko\Atk\Domain\Barang;
use Dots\Toko\Atk\Exception\ValidationException;
use Dots\Toko\Atk\Model\BarangAddRequest;
use Dots\Toko\Atk\Model\BarangUpdateRequest;
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

    public function add(){
        View::render('Barang/add', [
            'title' => 'Tambah Barang'
        ]);
    }

    public function postAddItem(){
        $request = new BarangAddRequest();
        $request->id = $_POST['id'];
        $request->nama = $_POST['nama'];
        $request->brand = $_POST['brand'];
        $request->stok = isset($_POST['stok']) ? (int) $_POST['stok'] : null; // Konversi ke integer
        $request->harga = isset($_POST['harga']) ? (float) $_POST['harga'] : null; // Konversi ke float jika diperlukan
        $request->idUser = $_POST['idUser'];

        try {
            $this->barangService->add($request);
            // redirect to users/login
            View::redirect('/barang/show');
        } catch (ValidationException $exception) {
            View::render('Barang/add', [
                'title' => 'Tambah Barang',
                'error' => $exception->getMessage()
            ]);
        }

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

    public function update(){

        $id = $_GET['id'] ?? null;

        $barang = $this->barangService->findBarangById($id);

        View::render('Barang/update', [
            "title" => "Update Barang",
            "barang" => [
                'id' => $barang->id,
                'nama' => $barang->nama,
                'brand' => $barang->brand,
                'stok' => $barang->stok,
                'harga' => $barang->harga,
                'idUser' => $barang->idUser

            ]
        ]);
    }

    public function postUpdate()
    {
        $request = new BarangUpdateRequest();
        $request->id = $_POST['id'];
        $request->nama = $_POST['nama'];
        $request->brand = $_POST['brand'];
        $request->stok = $_POST['stok'];
        $request->harga = $_POST['harga'];
        $request->idUser = $_POST['idUser'];

        try {
            $this->barangService->updateItem($request);
            View::redirect('/barang/show');
        } catch (ValidationException $exception) {
            View::render('Barang/update', [
                'title' => 'Update Barang',
                'error' => $exception->getMessage()
            ]);
        }
    }

    public function deleteBarang()
    {
        $id = $_GET['id'] ?? null;

        try {
            $this->barangService->deleteBarang($id);
            View::redirect('/barang/show');
        } catch (ValidationException $exception) {
            View::render('Barang/show', [
                'title' => 'Show Data',
                'error' => $exception->getMessage()
            ]);
        }
    }
}