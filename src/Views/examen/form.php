<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
  $isEdit = isset($examen) && !empty($examen->id);
  $action = $isEdit ? 'edit&id=' . $examen->id : 'new';
  $title = $isEdit ? 'Modifier un examen' : 'Ajouter un examen';
?>

<h1 class="mb-4"><?= $title ?></h1>

<form method="post" action="?controller=examen&action=<?= $action ?>">
  <div class="mb-3">
    <label for="nom" class="form-label">Nom</label>
    <input type="text" id="nom" name="nom" required class="form-control" value="<?= htmlspecialchars($examen->nom ?? '') ?>">
  </div>

  <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Mettre à jour' : 'Enregistrer' ?></button>
  <a href="?controller=examen&action=index" class="btn btn-secondary">Annuler</a>
</form>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
