<?php
// Chargement de l'autoloader Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Controllers\EtablissementController;


// Initialisation
$controller = new EtablissementController();

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    if ($controller->delete($id)) {
        header('Location: index.php?message=' . urlencode('Établissement supprimé avec succès'));
    } else {
        header('Location: index.php?error=' . urlencode('Impossible de supprimer l\'établissement car il est lié à des professeurs'));
    }
    exit();
}

// Récupération de la liste des établissements
$etablissements = $controller->index();

?>

<?php ob_start(); ?>

<div class="container">
    <h1 class="mb-4">Liste des établissements</h1>

    <?php // Affichage des messages de succès et d'erreur ?>
    <?php if (!empty($_GET['message'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['message']) ?></div>
    <?php endif; ?>

    <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Gestion des établissements</h2>
        <a href="form.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter un établissement
        </a>
    </div>

    <?php if (!empty($etablissements)) : ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Ville</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($etablissements as $etab) : ?>
                        <tr>
                            <td><?= htmlspecialchars($etab->getId()) ?></td>
                            <td><?= htmlspecialchars($etab->getNom()) ?></td>
                            <td><?= htmlspecialchars($etab->getVille() ?? '-') ?></td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="form.php?id=<?= $etab->getId() ?>" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="index.php" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet établissement ?')">
                                        <input type="hidden" name="id" value="<?= $etab->getId() ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Aucun établissement enregistré pour le moment.
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
        <a href="/app/index.php" class="btn btn-outline-secondary">
            <i class="bi bi-house-door"></i> Retour à l'accueil
        </a>
        <a href="/app/src/Views/professeur/index.php" class="btn btn-primary">
            Suivant: Gestion des Professeurs <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</div> <!-- Fin du container -->

<?php $content = ob_get_clean(); ?>

<?php require_once __DIR__ . '/../template/layout.php'; ?>

