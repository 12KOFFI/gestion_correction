<?php
// Chargement de l'autoloader Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// Importation des classes nécessaires
use App\Config\Database;
use App\Controllers\EtablissementController;
use App\Models\Etablissement;

// Initialisation
$controller = new EtablissementController();
$etablissement = new Etablissement(null, '', '');
$isEdit = false;
$error = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $nom = trim($_POST['nom'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    
    try {
        $etablissement = new Etablissement($id, $nom, $ville);
        
        // Création ou mise à jour
        if ($id) {
            $controller->edit($id, $etablissement);
            $message = 'Établissement mis à jour avec succès';
        } else {
            $controller->new($etablissement);
            $message = 'Établissement créé avec succès';
        }
        
        // Redirection vers la liste avec message de succès
        header('Location: index.php?message=' . urlencode($message));
        exit();
        
    } catch (Exception $e) {
        $error = 'Une erreur est survenue : ' . $e->getMessage();
    }
}

// Chargement des données si édition
if (isset($_GET['id'])) {
    $etablissement = $controller->getById((int)$_GET['id']);
    $isEdit = true;
}

// Configuration de la page
$pageTitle = $isEdit ? 'Modifier un établissement' : 'Ajouter un établissement';


// Affichage des messages
ob_start();

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

