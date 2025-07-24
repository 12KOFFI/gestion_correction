<?php require_once __DIR__ . '/../layout/header.php'; ?>

<h1 class="mb-4">Liste des professeurs</h1>

<a href="<?= BASE_URL ?>index.php?controller=professeur&action=new" class="btn btn-success mb-3">Ajouter un professeur</a>

<?php if (!empty($professeurs)) : ?>
  <table class="table table-striped table-hover align-middle">
    <thead class="table-primary">
      <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Grade</th>
        <th>Établissement</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($professeurs as $prof) : ?>
        <tr>
          <td><?= htmlspecialchars($prof->id) ?></td>
          <td><?= htmlspecialchars($prof->nom) ?></td>
          <td><?= htmlspecialchars($prof->prenom) ?></td>
          <td><?= htmlspecialchars($prof->grade) ?></td>
          <td><?= htmlspecialchars($prof->id_etab) ?></td> <!-- Plus tard remplacer par nom établissement -->
          <td>
            <a href="<?= BASE_URL ?>index.php?controller=professeur&action=edit&id=<?= $prof->id ?>" class="btn btn-sm btn-primary">Modifier</a>
            <a href="<?= BASE_URL ?>index.php?controller=professeur&action=delete&id=<?= $prof->id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else : ?>
  <div class="alert alert-info">Aucun professeur trouvé.</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
