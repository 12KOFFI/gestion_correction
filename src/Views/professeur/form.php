<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
  $isEdit = isset($professeur) && !empty($professeur->id);
  $action = $isEdit ? 'edit&id=' . $professeur->id : 'new';
  $title = $isEdit ? 'Modifier un professeur' : 'Ajouter un professeur';
?>

<h1 class="mb-4"><?= $title ?></h1>

<form method="post" action="?controller=professeur&action=<?= $action ?>">
  <div class="mb-3">
    <label for="nom" class="form-label">Nom</label>
    <input type="text" id="nom" name="nom" required class="form-control" value="<?= htmlspecialchars($professeur->nom ?? '') ?>">
  </div>

  <div class="mb-3">
    <label for="prenom" class="form-label">Prénom</label>
    <input type="text" id="prenom" name="prenom" required class="form-control" value="<?= htmlspecialchars($professeur->prenom ?? '') ?>">
  </div>

  <div class="mb-3">
    <label for="grade" class="form-label">Grade</label>
    <input type="text" id="grade" name="grade" required class="form-control" value="<?= htmlspecialchars($professeur->grade ?? '') ?>">
  </div>

  <div class="mb-3">
    <label for="id_etab" class="form-label">Établissement</label>
    <select id="id_etab" name="id_etab" class="form-select" required>
      <option value="">-- Sélectionnez un établissement --</option>
      <?php foreach ($etablissements as $etab) : ?>
        <option value="<?= $etab->id ?>"
          <?= (isset($professeur->id_etab) && $professeur->id_etab == $etab->id) ? 'selected' : '' ?>>
          <?= htmlspecialchars($etab->nom) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Mettre à jour' : 'Enregistrer' ?></button>
  <a href="?controller=professeur&action=index" class="btn btn-secondary">Annuler</a>
</form>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
