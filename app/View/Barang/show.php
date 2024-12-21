<div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <?php if (isset($model['error'])) { ?>
        <div class="row">
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($model['error']); ?>
            </div>
        </div>
    <?php } ?>

    <div class="row align-items-center g-lg-5 py-5">
        <div class="col-lg-7 text-center text-lg-start">
            <h1 class="display-4 fw-bold lh-1 mb-3">Menampilkan Data Barang </h1>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nama Barang</th>
                    <th scope="col">Brand</th>
                    <th scope="col">Stok</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($model['barang'])) { ?>
                    <?php foreach ($model['barang'] as $brg) { ?>
                        <tr>
                            <td><?= htmlspecialchars($brg->id); ?></td>
                            <td><?= htmlspecialchars($brg->nama); ?></td>
                            <td><?= htmlspecialchars($brg->brand); ?></td>
                            <td><?= htmlspecialchars($brg->stok); ?></td>
                            <td><?= htmlspecialchars($brg->harga); ?></td>
                            <td>
                                <a class="btn btn-success m-1" href="/users/edit?id=<?= htmlspecialchars($user->id); ?>">Ubah</a>

                                <a class="btn btn-danger m-1" href="/users/delete?id=<?= htmlspecialchars($user->id); ?>" onclick="return confirm('yakin?');">Hapus</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="2" class="text-center">No data available</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
