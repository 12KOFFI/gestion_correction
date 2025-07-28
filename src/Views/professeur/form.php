<?php
// Chargement de l'autoloader Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// Importation des contrôleurs nécessaires
use App\Controllers\ProfesseurController;
use App\Controllers\EtablissementController;

// Initialisation des contrôleurs
$professeurController = new ProfesseurController();
$etablissementController = new EtablissementController();

// Initialisation des variables
$professeur = [
    'id' => null,
    'nom' => '',
    'prenom' => '',
    'grade' => '',
    'etablissement_id' => null
];
$isEdit = false;
$title = 'Ajouter un professeur';

// Vérification si c'est une édition
if (isset($_GET['id'])) {
    $isEdit = true;
    $id = (int)$_GET['id'];
    
    // Si ce n'est pas une soumission de formulaire, on récupère le professeur depuis la base de données
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $profData = $professeurController->getById($id);
        
        if (!$profData) {
            header('Location: index.php?error=' . urlencode('Professeur non trouvé'));
            exit();
        }
        
        // Convertir l'objet en tableau
        $professeur = [
            'id' => $profData->getId(),
            'nom' => $profData->getNom(),
            'prenom' => $profData->getPrenom(),
            'grade' => $profData->getGrade(),
            'etablissement_id' => $profData->getEtablissementId()
        ];
        
        $title = 'Modifier le professeur : ' . htmlspecialchars($professeur['prenom'] . ' ' . $professeur['nom']);
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Préparation des données du formulaire
    $professeurData = [
        'nom' => $_POST['nom'] ?? '',
        'prenom' => $_POST['prenom'] ?? '',
        'grade' => $_POST['grade'] ?? '',
        'etablissement_id' => !empty($_POST['etablissement_id']) ? (int)$_POST['etablissement_id'] : null
    ];
    
    try {
        if ($isEdit) {
            // Mise à jour d'un professeur existant
            $professeur = $professeurController->edit((int)$_GET['id'], $professeurData);
            $message = 'Professeur mis à jour avec succès';
        } else {
            // Création d'un nouveau professeur
            $professeur = $professeurController->new($professeurData);
            $message = 'Professeur ajouté avec succès';
        }
        
        if ($professeur) {
            // Redirection vers la liste avec un message de succès
            header('Location: index.php?message=' . urlencode($message));
            exit();
        } else {
            $error = 'Une erreur est survenue lors de l\'enregistrement';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Récupération de la liste des établissements
$etablissements = $etablissementController->index();

// Inclusion du header
ob_start(); ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Affichage des messages d'erreur -->
            <?php if (isset($error)) : ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0"><?= $title ?></h2>
                </div>
                <div class="card-body">
                    <form method="post" action="form.php<?= $isEdit ? '?id=' . $professeur['id'] : '' ?>" novalidate>
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" id="nom" name="nom" required 
                                   class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>"
                                   value="<?= htmlspecialchars($professeur['nom']) ?>">
                            <?php if (isset($errors['nom'])) : ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['nom']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" id="prenom" name="prenom" required
                                   class="form-control <?= isset($errors['prenom']) ? 'is-invalid' : '' ?>"
                                   value="<?= htmlspecialchars($professeur['prenom']) ?>">
                            <?php if (isset($errors['prenom'])) : ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['prenom']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="grade" class="form-label">Grade <span class="text-danger">*</span></label>
                            <input type="text" id="grade" name="grade" required
                                   class="form-control <?= isset($errors['grade']) ? 'is-invalid' : '' ?>"
                                   value="<?= htmlspecialchars($professeur['grade']) ?>">
                            <?php if (isset($errors['grade'])) : ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['grade']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label for="etablissement_id" class="form-label">Établissement <span class="text-danger">*</span></label>
                            <select class="form-select <?= isset($errors['etablissement_id']) ? 'is-invalid' : '' ?>" 
                                    id="etablissement_id" name="etablissement_id" required>
                                <option value="">Sélectionner un établissement</option>
                                <?php foreach ($etablissements as $etablissement) : ?>
                                    <option value="<?= $etablissement->getId() ?>" <?= $professeur['etablissement_id'] == $etablissement->getId() ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($etablissement->getNom()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['etablissement_id'])) : ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($errors['etablissement_id']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between">
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
