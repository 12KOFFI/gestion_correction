<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Bienvenue sur le système de gestion de correction</h1>
        <p class="lead text-muted">Gérez facilement vos professeurs, examens, épreuves et corrections.</p>
        <hr class="my-4" style="width: 60px; border-width: 3px; border-color: #0d6efd;">
    </div>

    <div class="row justify-content-center g-4">

    <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-building fs-1 text-info mb-3"></i>
                    <h5 class="card-title">Gestion des Établissements</h5>
                    <p class="card-text text-muted">Gérez vos établissements scolaires et leurs villes.</p>
                    <a href="/app/index.php?controller=etablissement&action=index" class="btn btn-info text-white">Accéder</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill fs-1 text-primary mb-3"></i>
                    <h5 class="card-title">Gestion des Professeurs</h5>
                    <p class="card-text text-muted">Ajoutez, modifiez et supprimez les professeurs.</p>
                    <a href="/app/index.php?controller=professeur&action=index" class="btn btn-primary">Accéder</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-journal-text fs-1 text-success mb-3"></i>
                    <h5 class="card-title">Gestion des Examens</h5>
                    <p class="card-text text-muted">Organisez vos examens et matières facilement.</p>
                    <a href="/app/index.php?controller=examen&action=index" class="btn btn-success">Accéder</a>
                </div>
            </div>
        </div>

    <div class="row justify-content-center g-4 mt-4">
    <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-file-earmark-text fs-1 text-warning mb-3"></i>
                    <h5 class="card-title">Gestion des Épreuves</h5>
                    <p class="card-text text-muted">Suivez les différentes épreuves associées aux examens.</p>
                    <a href="/app/index.php?controller=epreuve&action=index" class="btn btn-warning">Accéder</a>
                </div>
            </div>
        </div>
   
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-pencil-square fs-1 text-danger mb-3"></i>
                    <h5 class="card-title">Gestion des Corrections</h5>
                    <p class="card-text text-muted">Suivez le processus de correction des copies.</p>
                    <a href="/app/index.php?controller=correction&action=index" class="btn btn-danger">Accéder</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
