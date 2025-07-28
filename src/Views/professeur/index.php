<?php
// Chargement de l'autoloader Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// Importation des classes nécessaires
use App\Controllers\ProfesseurController;
use App\Models\Professeur;

// Initialisation du contrôleur
$controller = new ProfesseurController();

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    if ($controller->delete($id)) {
        header('Location: index.php?message=' . urlencode('Professeur supprimé avec succès'));
    } else {
        header('Location: index.php?error=' . urlencode('Impossible de supprimer le professeur car il est lié à des données'));
    }
    exit();
}

// Récupération de la liste des professeurs
$professeurs = $controller->index();

// Inclusion du header
ob_start(); ?>

<div class="container py-4">
    <!-- Affichage des messages -->
    <?php if (isset($_GET['message'])) : ?>
        <div class="alert alert-success"><?= htmlspecialchars(urldecode($_GET['message'])) ?></div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])) : ?>
        <div class="alert alert-danger"><?= htmlspecialchars(urldecode($_GET['error'])) ?></div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Liste des professeurs</h1>
        <a href="form.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter un professeur
        </a>
    </div>

    <?php if (!empty($professeurs)) : ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Grade</th>
                        <th>Établissement</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($professeurs as $prof) : ?>
                        <tr>
                            <td><?= htmlspecialchars($prof->getId()) ?></td>
                            <td><?= htmlspecialchars($prof->getNom()) ?></td>
                            <td><?= htmlspecialchars($prof->getPrenom()) ?></td>
                            <td><?= htmlspecialchars($prof->getGrade()) ?></td>
                            <td>
                                <?php 
                                $etablissementId = $prof->getEtablissementId();
                                $etablissement = null;
                                if ($etablissementId) {
                                    $etablissementController = new \App\Controllers\EtablissementController();
                                    $etablissements = $etablissementController->index();
                                    foreach ($etablissements as $etab) {
                                        if ($etab->getId() === $etablissementId) {
                                            $etablissement = $etab;
                                            break;
                                        }
                                    }
                                }
                                echo htmlspecialchars($etablissement ? $etablissement->getNom() : 'Non défini');
                                ?>
                            </td>
                            <td class="text-end">
                                <a href="form.php?id=<?= $prof->getId() ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <form method="POST" action="index.php" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce professeur ?')">
                                    <input type="hidden" name="id" value="<?= $prof->getId() ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <div class="alert alert-info">Aucun professeur trouvé.</div>
    <?php endif; ?>

    <div class="d-flex justify-content-between mt-4">
        <a href="/app/src/Views/etablissement/index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Précédent: Établissements
        </a>
        <a href="/app/src/Views/examen/index.php" class="btn btn-primary">
            Suivant: Gestion des Examens <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php require_once __DIR__ . '/../template/layout.php'; ?>
