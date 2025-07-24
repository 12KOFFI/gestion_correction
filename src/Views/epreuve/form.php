<?php 
// Définir le titre de la page
$title = isset($epreuve) ? 'Modifier une épreuve' : 'Ajouter une épreuve';

// Inclure le header qui contient les balises HTML de base et les liens CSS
include_once __DIR__ . '/../layout/header.php';

// Debug: Afficher les informations de débogage
error_log("=== DÉBUT DE LA VUE FORMULAIRE ===");
error_log("Variable epreuve existe: " . (isset($epreuve) ? 'oui' : 'non'));
if (isset($epreuve)) {
    error_log("Type de epreuve: " . gettype($epreuve));
    error_log("Est un objet: " . (is_object($epreuve) ? 'oui' : 'non'));
    if (is_object($epreuve)) {
        error_log("Classe de l'objet: " . get_class($epreuve));
        error_log("Propriétés: " . print_r(get_object_vars($epreuve), true));
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow rounded">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><?= $title ?></h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" class="needs-validation" novalidate>
                        <?php if (isset($epreuve) && !empty($epreuve->id)) : ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars($epreuve->id) ?>">
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="bi bi-info-circle me-2"></i>Mode édition - ID: <?= htmlspecialchars($epreuve->id) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="bi bi-info-circle me-2"></i>Mode création d'une nouvelle épreuve
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="nom" class="form-label">Nom de l'épreuve <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nom" name="nom" required 
                                       value="<?= isset($epreuve) ? htmlspecialchars($epreuve->nom) : '' ?>">
                                <div class="invalid-feedback">
                                    Veuillez saisir le nom de l'épreuve.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="type" id="type" required>
                                    <option value="">-- Choisir --</option>
                                    <option value="écrit" <?= (isset($epreuve) && $epreuve->type === 'écrit') ? 'selected' : '' ?>>Écrit</option>
                                    <option value="oral" <?= (isset($epreuve) && $epreuve->type === 'oral') ? 'selected' : '' ?>>Oral</option>
                                </select>
                                <div class="invalid-feedback">
                                    Veuillez sélectionner un type d'épreuve.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="id_examen" class="form-label">Examen associé</label>
                                <select class="form-select" id="id_examen" name="id_examen">
                                    <option value="">-- Sélectionner un examen --</option>
                                    <?php foreach ($examens as $examen): ?>
                                        <option value="<?= $examen['id'] ?>" 
                                            <?= (isset($epreuve) && $epreuve->id_examen == $examen['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($examen['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="?controller=epreuve&action=index" class="btn btn-outline-secondary me-md-2">
                                <i class="bi bi-arrow-left me-1"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>
                                <?= (isset($epreuve) && !empty($epreuve->id)) ? 'Mettre à jour' : 'Enregistrer' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Validation du formulaire -->
<script>
// Désactive l'envoi du formulaire s'il y a des champs invalides
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

<?php include_once __DIR__ . '/../layout/footer.php'; ?>
