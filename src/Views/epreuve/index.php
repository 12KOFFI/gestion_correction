<?php include_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Liste des épreuves</h3>
        <a href="?controller=epreuve&action=new" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nouvelle épreuve
        </a>
    </div>

    <?php if (!empty($epreuves)) : ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>ID Examen</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($epreuves as $epreuve) : ?>
                        <tr>
                            <td><?= htmlspecialchars($epreuve['id']) ?></td>
                            <td><?= htmlspecialchars($epreuve['nom']) ?></td>
                            <td><?= htmlspecialchars($epreuve['type']) ?></td>
                            <td><?= htmlspecialchars($epreuve['id_examen']) ?></td>
                            <td>
                                <a href="?controller=epreuve&action=edit&id=<?= $epreuve['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                <a href="?controller=epreuve&action=delete&id=<?= $epreuve['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette épreuve ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <div class="alert alert-info">Aucune épreuve enregistrée.</div>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>
