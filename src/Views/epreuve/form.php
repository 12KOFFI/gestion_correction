<?php 
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
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white">
            <h4><?= isset($epreuve) ? 'Modifier une épreuve' : 'Ajouter une épreuve' ?></h4>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="post">
                <?php if (isset($epreuve) && !empty($epreuve->id)) : ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($epreuve->id) ?>">
                    <div class="alert alert-info">
                        Mode édition - ID: <?= htmlspecialchars($epreuve->id) ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Mode création d'une nouvelle épreuve</div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom de l'épreuve</label>
                    <input type="text" class="form-control" id="nom" name="nom" required 
                           value="<?= isset($epreuve) ? htmlspecialchars($epreuve->nom) : '' ?>">
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" name="type" id="type" required>
                        <option value="">-- Choisir --</option>
                        <option value="écrit" <?= (isset($epreuve) && $epreuve->type === 'écrit') ? 'selected' : '' ?>>Écrit</option>
                        <option value="oral" <?= (isset($epreuve) && $epreuve->type === 'oral') ? 'selected' : '' ?>>Oral</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="id_examen" class="form-label">Examen associé (ID)</label>
                    <input type="number" class="form-control" id="id_examen" name="id_examen" 
                           value="<?= isset($epreuve) ? htmlspecialchars($epreuve->id_examen) : '' ?>">
                </div>

                <button type="submit" class="btn btn-success">
                    <?= isset($epreuve) ? 'Mettre à jour' : 'Enregistrer' ?>
                </button>
                <a href="?controller=epreuve&action=index" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>
