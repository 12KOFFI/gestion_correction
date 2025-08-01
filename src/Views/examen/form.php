<?php
// Chargement de l'autoloader Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// Importation du contrôleur nécessaire
use App\Controllers\ExamenController;

// Initialisation
$controller = new ExamenController();
$examen = null;
$isEdit = false;
$error = '';
$message = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $nom = trim($_POST['nom'] ?? '');
    
    try {
        $isEdit = ($id !== null);
        
        // Validation des champs obligatoires
        if (empty($nom)) {
            throw new Exception('Le nom de l\'examen est obligatoire');
        }
        
        // Création ou mise à jour via le contrôleur
        if ($isEdit) {
            $examen = $controller->edit($id, ['nom' => $nom]);
            if (!$examen) {
                throw new Exception('Échec de la mise à jour de l\'examen. Vérifiez que l\'examen existe.');
            }
            $message = 'Examen mis à jour avec succès';
        } else {
            $examen = $controller->new(['nom' => $nom]);
            $message = 'Examen créé avec succès';
        }
        
        // Redirection vers la liste avec message de succès
        header('Location: index.php?message=' . urlencode($message));
        exit();
        
    } catch (Exception $e) {
        $error = 'Erreur : ' . $e->getMessage();
    }
}

// Chargement des données si édition
if (isset($_GET['id'])) {
    $examenId = (int)$_GET['id'];
    $examen = $controller->getById($examenId);
    
    if (!$examen) {
        $error = "L'examen demandé n'existe pas.";
    } else {
        $isEdit = true;
    }
}

// Configuration de la page
$title = $isEdit ? 'Modifier un examen' : 'Ajouter un examen';

 ?>

 <?php ob_start(); ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="h4 mb-0"><?= $title ?></h2>
                </div>
                <div class="card-body">
                    <form method="post" action="form.php<?= $isEdit ? '?id=' . $examen->getId() : '' ?>" class="needs-validation" novalidate>
                        <?php if ($isEdit): ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars($examen->getId()) ?>">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom de l'examen <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>" 
                                   id="nom" 
                                   name="nom" 
                                   value="<?= htmlspecialchars($examen->getNom() ?? ''); ?>"
                                   required>
                            <?php if (isset($errors['nom'])): ?>
                                <div class="invalid-feedback">
                                    <?= htmlspecialchars($errors['nom']) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Retour à la liste
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> <?= $isEdit ? 'Mettre à jour' : 'Enregistrer' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php require_once __DIR__ . '/../template/layout.php'; ?>
