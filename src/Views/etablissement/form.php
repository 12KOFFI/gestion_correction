<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
  $isEdit = isset($etablissement) && !empty($etablissement->id);
  $action = $isEdit ? 'edit&id=' . $etablissement->id : 'new';
  $title = $isEdit ? 'Modifier un établissement' : 'Ajouter un établissement';
?>

<h1 class="mb-4"><?= $title ?></h1>

<form method="post" action="?controller=etablissement&action=<?= $action ?>">
  <div class="mb-3">
    <label for="nom" class="form-label">Nom</label>
    <input type="text" id="nom" name="nom" required class="form-control" value="<?= htmlspecialchars($etablissement->nom ?? '') ?>">
  </div>

  <div class="mb-3">
    <label for="ville" class="form-label">Ville</label>
    <input type="text" id="ville" name="ville" class="form-control" value="<?= htmlspecialchars($etablissement->ville ?? '') ?>">
  </div>

  <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Mettre à jour' : 'Enregistrer' ?></button>
  <a href="?controller=etablissement&action=index" class="btn btn-secondary">Annuler</a>
</form>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
