<?php require_once __DIR__ . '/../layout/header.php'; ?>

<h1 class="mb-4">Liste des établissements</h1>

<a href="?controller=etablissement&action=new" class="btn btn-success mb-3">Ajouter un établissement</a>

<?php if (!empty($etablissements)) : ?>
  <table class="table table-striped table-hover align-middle">
    <thead class="table-primary">
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Ville</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($etablissements as $etablissement) : ?>
        <tr>
          <td><?= htmlspecialchars($etablissement->id) ?></td>
          <td><?= htmlspecialchars($etablissement->nom) ?></td>
          <td><?= htmlspecialchars($etablissement->ville ?? '-') ?></td>
          <td>
            <a href="?controller=etablissement&action=edit&id=<?= $etablissement->id ?>" class="btn btn-sm btn-primary">Modifier</a>
            <a href="?controller=etablissement&action=delete&id=<?= $etablissement->id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else : ?>
  <div class="alert alert-info">Aucun établissement trouvé.</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
