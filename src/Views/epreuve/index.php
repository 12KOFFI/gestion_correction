<?php
// Chargement de l'autoloader Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// Importation des classes nécessaires
use App\Config\Database;
use App\Controllers\EpreuveController;
use App\Models\Epreuve;

// Initialisation
$controller = new EpreuveController();

// Récupération de la liste
$epreuves = $controller->index();

// Debug: Afficher le contenu de $epreuves
// echo "<pre>";
// var_dump($epreuves);
// echo "</pre>";

// En-tête
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Liste des épreuves</h1>
        <a href="form.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter une épreuve
        </a>
    </div>

    <?php if (!empty($epreuves)) : ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>ID Examen</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($epreuves as $epreuve) : ?>
                        <tr>
                            <td><?= htmlspecialchars($epreuve->getId()) ?></td>
                            <td><?= htmlspecialchars($epreuve->getNom()) ?></td>
                            <td>
                                <span class="badge bg-<?= $epreuve->getType() === 'écrit' ? 'info' : 'success' ?>">
                                    <?= htmlspecialchars(ucfirst($epreuve->getType())) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($epreuve->getIdExamen() ?? 'N/A') ?></td>
                            <td class="text-end">
                                <a href="form.php?id=<?= $epreuve->getId() ?>" 
                                   class="btn btn-sm btn-primary me-2"
                                   title="Modifier cette épreuve">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <a href="?controller=epreuve&action=delete&id=<?= $epreuve->getId() ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette épreuve ?')">
                                    <i class="bi bi-trash"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <div class="alert alert-info">Aucune épreuve trouvée.</div>
    <?php endif; ?>

    <div class="d-flex justify-content-between mt-4">
        <a href="/app/src/Views/examen/index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Précédent: Examens
        </a>
        <a href="/app/src/Views/correction/index.php" class="btn btn-primary">
            Suivant: Gestion des Corrections <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
