<?php
// Chargement de l'autoloader Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// Importation des classes nécessaires
use App\Controllers\EtablissementController;

// Initialisation
$controller = new EtablissementController();
$etablissement = null;
$isEdit = false;
$error = '';
$message = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : null;
    $data = [
        'nom' => trim($_POST['nom'] ?? ''),
        'ville' => trim($_POST['ville'] ?? '')
    ];

    try {
        // Validation des données
        if (empty($data['nom']) || empty($data['ville'])) {
            throw new Exception('Tous les champs sont obligatoires');
        }

        // Création ou mise à jour
        if ($id) {
            $etablissement = $controller->edit($id, $data);
            if (!$etablissement) {
                throw new Exception("Échec de la mise à jour de l'établissement. Vérifiez que l'établissement existe.");
            }
            $message = 'Établissement mis à jour avec succès';
        } else {
            $etablissement = $controller->new($data);
            $message = 'Établissement créé avec succès';
        }

        // Redirection vers la liste avec message de succès
        header('Location: index.php?message=' . urlencode($message));
        exit();
    } catch (Exception $e) {
        $error = 'Une erreur est survenue : ' . $e->getMessage();
        // Si c'est une édition, on récupère les données de l'établissement pour les afficher
        if (isset($id)) {
            $etablissement = (object)array_merge(['id' => $id], $data);
            $isEdit = true;
        }
    }
}

// Chargement des données si édition
if (isset($_GET['id'])) {
    $etablissement = $controller->getById((int) $_GET['id']);
    $isEdit = true;
}

// Configuration de la page
$pageTitle = $isEdit ? 'Modifier un établissement' : 'Ajouter un établissement';

ob_start();

// Affichage des messages
if (!empty($error)) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
}
?>

<div class="container">
    <h1 class="mb-4"><?= $pageTitle ?></h1>

    <form method="post">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($etablissement->getId()) ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom"
                value="<?= htmlspecialchars($etablissement->getNom()) ?>" required>
        </div>

        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" class="form-control" id="ville" name="ville"
                value="<?= htmlspecialchars($etablissement->getVille()) ?>" required>
        </div>

        <div class="form-group mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> <?= $isEdit ? 'Mettre à jour' : 'Enregistrer' ?>
            </button>
            <a href="index.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </form>
</div> <!-- Fin du container -->

<?php $content = ob_get_clean(); ?>

<?php require_once __DIR__ . '/../template/layout.php'; ?>