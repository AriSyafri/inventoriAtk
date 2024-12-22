<div class="container col-xl-10 col-xxl-8 px-4 py-5">

    <?php if(isset($model['error'])) { ?>
        
        <div class="row">
            <div class="alert alert-danger" role="alert">
                <?= $model['error'] ?>
            </div>
        </div>
        
    <?php } ?> 

    <div class="row align-items-center g-lg-5 py-5">
        <div class="col-lg-7 text-center text-lg-start">
            <h1 class="display-4 fw-bold lh-1 mb-3">Update Barang</h1>
            <p class="col-lg-10 fs-4">by <a target="_blank" href="https://www.programmerzamannow.com/">Programmer Zaman
                Now</a></p>
        </div>
        <div class="col-md-10 mx-auto col-lg-5">
            <form class="p-4 p-md-5 border rounded-3 bg-light" method="post" action="/barang/update">
                <div class="form-floating mb-3">
                    <input name="id" type="text" class="form-control" id="id" placeholder="id" value="<?= htmlspecialchars($model['barang']['id'] ?? '') ?>" readonly>
                    <label for="id">Id</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="nama" type="text" class="form-control" id="nama" placeholder="nama" value="<?= htmlspecialchars($model['barang']['nama'] ?? '') ?>">
                    <label for="nama">Nama</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="brand" type="text" class="form-control" id="brand" placeholder="brand" value="<?= htmlspecialchars($model['barang']['brand'] ?? '') ?>">
                    <label for="brand">Brand</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="stok" type="text" class="form-control" id="stok" placeholder="stok" value="<?= htmlspecialchars($model['barang']['stok'] ?? '') ?>">
                    <label for="stok">Stok</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="harga" type="text" class="form-control" id="harga" placeholder="harga" value="<?= htmlspecialchars($model['barang']['harga'] ?? '') ?>">
                    <label for="harga">Harga</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="idUser" type="text" class="form-control" id="idUser" placeholder="idUser" value="<?= htmlspecialchars($model['barang']['idUser'] ?? '') ?>">
                    <label for="idUser">Id User</label>
                </div>

                <button class="w-100 btn btn-lg btn-primary" type="submit">Update Data</button>
            </form>
        </div>
    </div>
</div>