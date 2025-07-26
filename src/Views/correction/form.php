<?php
// Chargement de l'autoloader Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// Importation des classes nécessaires
use App\Controllers\CorrectionController;
use App\Controllers\ProfesseurController;
use App\Controllers\EpreuveController;
use App\Models\Correction;

// Initialisation
$correctionController = new CorrectionController();
$professeurController = new ProfesseurController();
$epreuveController = new EpreuveController();

// Initialisation des variables
$correction = null;
$error = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
        $id_professeur = (int)$_POST['id_professeur'];
        $id_epreuve = (int)$_POST['id_epreuve'];
        $date = $_POST['date'];
        $nbr_copie = (int)$_POST['nbr_copie'];
        
        // Validation des données
        if (empty($id_professeur) || empty($id_epreuve) || empty($date) || $nbr_copie <= 0) {
            throw new Exception("Tous les champs sont obligatoires et le nombre de copies doit être supérieur à zéro.");
        }
        
        // Création ou mise à jour de la correction
        if ($id) {
            // Mise à jour
            $correction = $correctionController->getById($id);
            if (!$correction) {
                throw new Exception("La correction à mettre à jour n'existe pas.");
            }
            $data = [
                'id_professeur' => $id_professeur,
                'id_epreuve' => $id_epreuve,
                'date' => $date,
                'nbr_copie' => $nbr_copie
            ];
            
            $updatedCorrection = $correctionController->edit($id, $data);
            if (!$updatedCorrection) {
                throw new Exception("Échec de la mise à jour de la correction.");
            }
            $message = 'Correction mise à jour avec succès';
        } else {
            // Création
            $data = [
                'id_professeur' => $id_professeur,
                'id_epreuve' => $id_epreuve,
                'date' => $date,
                'nbr_copie' => $nbr_copie
            ];
            $correction = $correctionController->new($data);
            $message = 'Correction créée avec succès';
        }
        
        // Redirection vers la liste avec message de succès
        header('Location: index.php?success=' . urlencode($message));
        exit();
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Récupération des données nécessaires
try {
    $professeurs = $professeurController->index();
    $epreuves = $epreuveController->index();
    
    // Chargement des données si édition (et pas de soumission de formulaire en cours)
    if (empty($_POST) && isset($_GET['id'])) {
        $correctionId = (int)$_GET['id'];
        $correction = $correctionController->getById($correctionId);
        
        if (!$correction) {
            throw new Exception("La correction demandée n'existe pas.");
        }
    }
    
} catch (Exception $e) {
    $error = $e->getMessage();
    // Si on est en mode édition et que la correction n'existe pas, on redirige vers la liste
    if (isset($correctionId)) {
        header('Location: index.php?error=' . urlencode($error));
        exit();
    }
}

// En-tête
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container py-4">
    <h2><?= $correction ? 'Modifier une Correction' : 'Nouvelle Correction' ?></h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            <?= htmlspecialchars(urldecode($_GET['success'])) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <form method="post" class="mt-4">
        <?php if ($correction): ?>
            <input type="hidden" name="id" value="<?= $correction->getId() ?>">
        <?php endif; ?>
        <div class="mb-3">
            <label for="id_professeur" class="form-label">Professeur</label>
            <select name="id_professeur" id="id_professeur" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php if (is_array($professeurs)): ?>
                    <?php foreach ($professeurs as $prof): ?>
                        <?php 
                        // Utilisation des getters des objets modèles
                        $id = is_array($prof) ? ($prof['id'] ?? null) : $prof->getId();
                        $prenom = is_array($prof) ? ($prof['prenom'] ?? '') : $prof->getPrenom();
                        $nom = is_array($prof) ? ($prof['nom'] ?? '') : $prof->getNom();
                        $nomComplet = trim("$prenom $nom");
                        $selected = ($correction && $correction->getIdProfesseur() == $id) ? 'selected' : '';
                        ?>
                        <option value="<?= htmlspecialchars($id) ?>" <?= $selected ?>>
                            <?= htmlspecialchars($nomComplet) ?>
                        </option>
                    <?php endforeach ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_epreuve" class="form-label">Épreuve</label>
            <select name="id_epreuve" id="id_epreuve" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php if (is_array($epreuves)): ?>
                    <?php foreach ($epreuves as $e): ?>
                        <?php 
                        // Utilisation des getters des objets modèles
                        $id = is_array($e) ? ($e['id'] ?? null) : $e->getId();
                        $nom = is_array($e) ? ($e['nom'] ?? 'Inconnu') : $e->getNom();
                        $selected = ($correction && $correction->getIdEpreuve() == $id) ? 'selected' : '';
                        ?>
                        <option value="<?= htmlspecialchars($id) ?>" <?= $selected ?>>
                            <?= htmlspecialchars($nom) ?>
                        </option>
                    <?php endforeach ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" name="date" id="date"
                   value="<?= $correction ? htmlspecialchars($correction->getDate()) : '' ?>" required>
        </div>

        <div class="mb-3">
            <label for="nbr_copie" class="form-label">Nombre de copies</label>
            <input type="number" class="form-control" name="nbr_copie" id="nbr_copie"
                   value="<?= $correction ? htmlspecialchars($correction->getNbrCopie()) : '' ?>" required>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="bi bi-save me-1"></i> Enregistrer
        </button>
        <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-x-circle me-1"></i> Annuler
        </a>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
