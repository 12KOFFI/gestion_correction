<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container py-4">
    <h2><?= $correction ? 'Modifier une Correction' : 'Nouvelle Correction' ?></h2>

    <form method="post" class="mt-4">
        <div class="mb-3">
            <label for="id_professeur" class="form-label">Professeur</label>
            <select name="id_professeur" id="id_professeur" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php if (is_array($professeurs)): ?>
                    <?php foreach ($professeurs as $prof): ?>
                        <?php 
                        // Gérer à la fois les tableaux et les objets
                        $id = is_array($prof) ? ($prof['id'] ?? null) : ($prof->id ?? null);
                        $prenom = is_array($prof) ? ($prof['prenom'] ?? '') : ($prof->prenom ?? '');
                        $nom = is_array($prof) ? ($prof['nom'] ?? '') : ($prof->nom ?? '');
                        $nomComplet = trim("$prenom $nom");
                        $selected = ($correction && $correction->id_professeur == $id) ? 'selected' : '';
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
                        // Gérer à la fois les tableaux et les objets
                        $id = is_array($e) ? ($e['id'] ?? null) : ($e->id ?? null);
                        $nom = is_array($e) ? ($e['nom'] ?? 'Inconnu') : ($e->nom ?? 'Inconnu');
                        $selected = ($correction && $correction->id_epreuve == $id) ? 'selected' : '';
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
