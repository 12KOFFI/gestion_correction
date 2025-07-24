<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Liste des examens</h1>
    <a href="?controller=examen&action=new" class="btn btn-primary">Nouvel examen</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($examens as $examen): ?>
            <tr>
                <td><?= $examen['id'] ?></td>
                <td><?= $examen['nom'] ?></td>
                <td>
                    <a href="?controller=examen&action=edit&id=<?= $examen['id'] ?>" 
                       class="btn btn-sm btn-primary">Modifier</a>
                    <a href="?controller=examen&action=delete&id=<?= $examen['id'] ?>" 
                       class="btn btn-sm btn-danger" 
                       onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
