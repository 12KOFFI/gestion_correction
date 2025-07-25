<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Liste des Corrections</h2>
        <a href="?controller=correction&action=new" class="btn btn-primary">Nouvelle Correction</a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Professeur</th>
                <th>Épreuve</th>
                <th>Date</th>
                <th>Nombre de copies</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($corrections as $correction): ?>
                <tr>
                    <td><?= htmlspecialchars($correction->id) ?></td>
                    <td><?= htmlspecialchars($correction->prof_prenom . ' ' . $correction->prof_nom) ?></td>
                    <td><?= htmlspecialchars($correction->epreuve_nom) ?></td>
                    <td><?= htmlspecialchars($correction->date) ?></td>
                    <td><?= htmlspecialchars($correction->nbr_copie) ?></td>
                    <td>
                        <a href="?controller=correction&action=edit&id=<?= $correction->id ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <a href="?controller=correction&action=delete&id=<?= $correction->id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between mt-4">
    <a href="/app/index.php?controller=epreuve&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Précédent: Épreuves
    </a>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
