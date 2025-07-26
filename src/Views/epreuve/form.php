<?php
// Chargement de l'autoloader Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// Importation des classes nécessaires
use App\Config\Database;
use App\Controllers\EpreuveController;
use App\Controllers\ExamenController;
use App\Models\Epreuve;

// Initialisation
$epreuveController = new EpreuveController();
$examenController = new ExamenController();

// Récupérer la liste des examens
try {
    $examens = $examenController->index();
} catch (\Exception $e) {
    $error = 'Erreur lors du chargement des examens : ' . $e->getMessage();
    $examens = [];
}

$epreuve = new Epreuve(null, '', '', null);
$isEdit = false;
$errors = [];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $nom = trim($_POST['nom'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $id_examen = !empty($_POST['id_examen']) ? (int)$_POST['id_examen'] : null;
    
    try {
        $epreuve = new Epreuve($id, $nom, $type, $id_examen);
        $isEdit = ($id !== null);
        
        // Validation des champs obligatoires
        if (empty($nom)) {
            throw new Exception('Le nom de l\'épreuve est obligatoire');
        }
        
        if (empty($type)) {
            throw new Exception('Le type d\'épreuve est obligatoire');
        }
        
        // Création ou mise à jour
        if ($isEdit) {
            $success = $epreuveController->edit($id, $epreuve);
            if (!$success) {
                throw new Exception('Échec de la mise à jour de l\'épreuve. Vérifiez que l\'épreuve existe.');
            }
            $message = 'Épreuve mise à jour avec succès';
        } else {
            $epreuve = $epreuveController->new($epreuve);
            $message = 'Épreuve créée avec succès';
        }
        
        // Redirection vers la liste avec message de succès
        header('Location: index.php?message=' . urlencode($message));
        exit();
        
    } catch (Exception $e) {
        $error = 'Erreur : ' . $e->getMessage();
        // Si c'est une erreur de validation, on reste sur le formulaire avec les données saisies
        if (!empty($id)) {
            header('Location: form.php?id=' . $id . '&error=' . urlencode($error));
        } else {
            header('Location: form.php?error=' . urlencode($error));
        }
        exit();
    }
}

// Chargement des données si édition
if (isset($_GET['id'])) {
    try {
        $epreuveId = (int)$_GET['id'];
        $epreuve = $epreuveController->getById($epreuveId);
        
        if (!$epreuve) {
            throw new Exception("L'épreuve demandée n'existe pas.");
        }
        
        $isEdit = true;
        error_log("Chargement de l'épreuve ID: " . $epreuve->getId() . ", Nom: " . $epreuve->getNom());
    } catch (Exception $e) {
        $error = $e->getMessage();
        // Si on est en mode édition et que l'épreuve n'existe pas, on redirige vers la liste
        if (isset($epreuveId)) {
            header('Location: index.php?error=' . urlencode($error));
            exit();
        }
    }
}

// Configuration de la page
$title = $isEdit ? 'Modifier une épreuve' : 'Ajouter une épreuve';

// En-tête
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="h4 mb-0"><?= $title ?></h2>
                </div>
                <div class="card-body">
                    <?php if (isset($error) && !empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?= htmlspecialchars(urldecode($_GET['error'])) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" action="form.php<?= $isEdit ? '?id=' . $epreuve->getId() : '' ?>" class="needs-validation" novalidate>
                        
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom de l'épreuve <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>" 
                                   id="nom" 
                                   name="nom" 
                                   value="<?= htmlspecialchars($epreuve->getNom() ?? '') ?>"
                                   required>
                            <?php if (isset($errors['nom'])): ?>
                                <div class="invalid-feedback">
                                    <?= htmlspecialchars($errors['nom']) ?>
                                </div>
                            <?php else: ?>
                                <div class="invalid-feedback">
                                    Veuillez saisir le nom de l'épreuve.
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($errors['type']) ? 'is-invalid' : '' ?>" 
                                        id="type" 
                                        name="type" 
                                        required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="écrit" <?= ($epreuve->getType() ?? '') === 'écrit' ? 'selected' : '' ?>>Écrit</option>
                                    <option value="oral" <?= ($epreuve->getType() ?? '') === 'oral' ? 'selected' : '' ?>>Oral</option>
                                </select>
                                <?php if (isset($errors['type'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['type']) ?>
                                    </div>
                                <?php else: ?>
                                    <div class="invalid-feedback">
                                        Veuillez sélectionner un type d'épreuve.
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="id_examen" class="form-label">Examen associé</label>
                                <select class="form-select <?= isset($errors['id_examen']) ? 'is-invalid' : '' ?>" 
                                        id="id_examen" 
                                        name="id_examen">
                                    <option value="">Sélectionnez un examen (optionnel)</option>
                                    <?php foreach ($examens as $examen): ?>
                                        <option value="<?= $examen->getId() ?>" 
                                                <?= ($epreuve->getIdExamen() ?? '') == $examen->getId() ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($examen->getNom()) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['id_examen'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['id_examen']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="?controller=epreuve&action=index" class="btn btn-outline-secondary">
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

<script>
// Validation côté client
(function () {
    'use strict'
    
    // Récupère tous les formulaires auxquels nous voulons appliquer le style de validation Bootstrap
    var forms = document.querySelectorAll('.needs-validation')
    
    // Boucle sur chaque formulaire pour empêcher la soumission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
