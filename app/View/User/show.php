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
            <h1 class="display-4 fw-bold lh-1 mb-3">Show Data User</h1>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($model['users'])) { ?>
                    <?php foreach ($model['users'] as $user) { ?>
                        <tr>
                            <td><?= htmlspecialchars($user->id); ?></td>
                            <td><?= htmlspecialchars($user->name); ?></td>
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
