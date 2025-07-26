<?php
// Chargement de l'autoloader Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// Importation des classes nécessaires
use App\Config\Database;
use App\Controllers\EtablissementController;
use App\Models\Etablissement;

// Initialisation
$controller = new EtablissementController();

// Traitement des actions
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'delete':
            if (isset($_GET['id'])) {
                $id = (int)$_GET['id'];
                if ($controller->delete($id)) {
                    header('Location: index.php?message=' . urlencode('Établissement supprimé avec succès'));
                } else {
                    header('Location: index.php?error=' . urlencode('Impossible de supprimer l\'établissement car il est lié à des professeurs'));
                }
                exit();
            }
            break;
    }
}

// Récupération de la liste
$etablissements = $controller->index();

// En-tête
require_once __DIR__ . '/../layout/header.php';
?>

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
                                    <a href="index.php?action=delete&id=<?= $etab->getId() ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet établissement ?')"
                                       title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </a>
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

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
