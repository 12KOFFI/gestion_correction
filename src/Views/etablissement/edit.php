<?php
// Supposons que vous ayez déjà récupéré l'établissement à modifier dans une variable $etablissement
?>

<form method="POST">
    <input type="hidden" name="id" value="<?= $etablissement['id'] ?>">
    <!-- Ajoutez ici les autres champs de votre formulaire -->
    <button type="submit">Mettre à jour l'établissement</button>
</form>