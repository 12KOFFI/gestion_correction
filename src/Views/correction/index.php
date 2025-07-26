<?php
// Chargement de l'autoloader Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// Importation des classes nécessaires
use App\Controllers\CorrectionController;

// Initialisation
$correctionController = new CorrectionController();

// Récupération des corrections
try {
    $corrections = $correctionController->index();
} catch (Exception $e) {
    $error = $e->getMessage();
}

// En-tête
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Liste des Corrections</h2>
        <a href="form.php" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Nouvelle Correction
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            <?= htmlspecialchars(urldecode($_GET['success'])) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

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
                    <td><?= htmlspecialchars($correction->getId()) ?></td>
                    <td>
                        <?= !empty($correction->professeur_nom) ? htmlspecialchars($correction->professeur_nom) : 'ID: ' . $correction->getIdProfesseur() ?>
                    </td>
                    <td>
                        <?= !empty($correction->epreuve_nom) ? htmlspecialchars($correction->epreuve_nom) : 'ID: ' . $correction->getIdEpreuve() ?>
                    </td>
                    <td><?= htmlspecialchars($correction->getDate()) ?></td>
                    <td><?= htmlspecialchars($correction->getNbrCopie()) ?></td>
                    <td>
                        <a href="form.php?id=<?= $correction->getId() ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $correction->getId() ?>)">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-between mt-4">
    <a href="/app/src/Views/epreuve/index.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Précédent: Épreuves
    </a>
    <a href="/app/index.php" class="btn btn-outline-primary">
        <i class="bi bi-house-door"></i> Page d'accueil
    </a>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>

<script>
function confirmDelete(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette correction ?')) {
        window.location.href = 'delete.php?id=' + id;
    }
}
</script>
