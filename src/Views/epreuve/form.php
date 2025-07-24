<?php include_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow rounded">
        <div class="card-header bg-primary text-white">
            <h4><?= isset($epreuve) ? 'Modifier une épreuve' : 'Ajouter une épreuve' ?></h4>
        </div>
        <div class="card-body">
            <form method="post">
                <?php if (isset($epreuve) && !empty($epreuve->id)) : ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($epreuve->id) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom de l’épreuve</label>
                    <input type="text" class="form-control" id="nom" name="nom" required value="<?= $epreuve->nom ?? '' ?>">
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
                    <input type="number" class="form-control" id="id_examen" name="id_examen" value="<?= $epreuve->id_examen ?? '' ?>">
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
