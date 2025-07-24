<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container py-4">
    <h2><?= $correction ? 'Modifier une Correction' : 'Nouvelle Correction' ?></h2>

    <form method="post" class="mt-4">
        <div class="mb-3">
            <label for="id_professeur" class="form-label">Professeur</label>
            <select name="id_professeur" id="id_professeur" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($professeurs as $prof): ?>
                    <option value="<?= $prof->id ?>"
                        <?= ($correction && $correction->id_professeur == $prof->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($prof->prenom . ' ' . $prof->nom) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_epreuve" class="form-label">Épreuve</label>
            <select name="id_epreuve" id="id_epreuve" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($epreuves as $e): ?>
                    <option value="<?= $e->id ?>"
                        <?= ($correction && $correction->id_epreuve == $e->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($e->nom) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" name="date" id="date"
                   value="<?= $correction ? $correction->date : '' ?>" required>
        </div>

        <div class="mb-3">
            <label for="nbr_copie" class="form-label">Nombre de copies</label>
            <input type="number" class="form-control" name="nbr_copie" id="nbr_copie"
                   value="<?= $correction ? $correction->nbr_copie : '' ?>" required>
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="?controller=correction&action=index" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
