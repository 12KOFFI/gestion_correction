<?php require_once __DIR__ . '/../layout/header.php'; ?>

<h1 class="mb-4">Liste des examens</h1>

<a href="?controller=examen&action=create" class="btn btn-success mb-3">Ajouter un examen</a>

<?php if (!empty($examens)) : ?>
  <table class="table table-striped table-hover align-middle">
    <thead class="table-primary">
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($examens as $examen) : ?>
        <tr>
          <td><?= htmlspecialchars($examen['id']) ?></td>
          <td><?= htmlspecialchars($examen['nom']) ?></td>
          <td>
            <a href="?controller=examen&action=edit&id=<?= $examen['id'] ?>" class="btn btn-sm btn-primary">Modifier</a>
            <a href="?controller=examen&action=delete&id=<?= $examen['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else : ?>
  <div class="alert alert-info">Aucun examen trouvé.</div>
<?php endif; ?>

<div class="d-flex justify-content-between mt-4">
    <a href="/app/index.php?controller=professeur&action=index" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Précédent: Professeurs
    </a>
    <a href="/app/index.php?controller=epreuve&action=index" class="btn btn-primary">
        Suivant: Gestion des Épreuves <i class="bi bi-arrow-right"></i>
    </a>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
