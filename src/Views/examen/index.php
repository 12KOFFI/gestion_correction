<?php
// Chargement de l'autoloader Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// Importation des classes nécessaires
use App\Config\Database;
use App\Controllers\ExamenController;
use App\Models\Examen;

// Initialisation
$controller = new ExamenController();

// Traitement de la suppression si nécessaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    error_log('Tentative de suppression - ID reçu: ' . $_POST['delete_id']);
    $id = (int)$_POST['delete_id'];
    error_log('ID après conversion: ' . $id);
    
    try {
        $success = $controller->delete($id);
        error_log('Résultat de la suppression: ' . ($success ? 'succès' : 'échec'));
        
        if ($success) {
            $message = 'L\'examen a été supprimé avec succès';
        } else {
            $message = 'Erreur lors de la suppression de l\'examen';
        }
    } catch (Exception $e) {
        error_log('Exception lors de la suppression: ' . $e->getMessage());
        $message = 'Erreur technique lors de la suppression';
    }
    
    // Recharger la page avec un message
    header('Location: index.php?message=' . urlencode($message));
    exit();
}

// Récupération de la liste des examens
$examens = $controller->index();

// Affichage d'un message de succès s'il y en a un
$message = $_GET['message'] ?? null;

// En-tête
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container mt-4">
    <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Liste des examens</h1>
        <a href="form.php" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Ajouter un examen
        </a>
    </div>

    <?php if (!empty($examens)) : ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($examens as $examen) : ?>
                        <tr>
                            <td><?= htmlspecialchars($examen->getId()) ?></td>
                            <td><?= htmlspecialchars($examen->getNom()) ?></td>
                            <td class="text-end">
                                <a href="form.php?id=<?= $examen->getId() ?>" 
                                   class="btn btn-sm btn-primary me-2"
                                   title="Modifier cet examen">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <form method="POST" action="index.php" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet examen ?')">
                                    <input type="hidden" name="delete_id" value="<?= $examen->getId() ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer cet examen">
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
        <div class="alert alert-info">Aucun examen trouvé.</div>
    <?php endif; ?>

    <div class="d-flex justify-content-between mt-4">
        <a href="/app/src/Views/professeur/index.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Précédent: Professeurs
        </a>
        <a href="/app/src/Views/epreuve/index.php" class="btn btn-primary">
            Suivant: Gestion des Épreuves <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
