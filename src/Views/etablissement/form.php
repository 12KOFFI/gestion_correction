<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
  $isEdit = isset($etablissement) && $etablissement->getId() !== null;
  $action = $isEdit ? 'edit' : 'new';
  $title = $isEdit ? 'Modifier un établissement' : 'Ajouter un établissement';
?>

<h1 class="mb-4"><?= $title ?></h1>

<form method="post">
  <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= htmlspecialchars($etablissement->getId()) ?>">
  <?php endif; ?>
  
  <div class="mb-3">
    <label for="nom" class="form-label">Nom</label>
    <input type="text" class="form-control" id="nom" name="nom" 
           value="<?= htmlspecialchars($etablissement->getNom()) ?>" required>
  </div>
  
  <div class="mb-3">
    <label for="ville" class="form-label">Ville</label>
    <input type="text" class="form-control" id="ville" name="ville" 
           value="<?= htmlspecialchars($etablissement->getVille()) ?>" required>
  </div>

  <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Mettre à jour' : 'Enregistrer' ?></button>
  <a href="index.php?controller=etablissement&action=index" class="btn btn-secondary">Annuler</a>
</form>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
