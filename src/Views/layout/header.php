<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= $title ?? 'Gestion Professeurs' ?></title>

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Ajoute ceci dans ton <head> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <style>
      body {
        padding-top: 70px; /* espace pour la navbar */
      }
      footer {
        background-color: #f8f9fa;
        padding: 15px 0;
        text-align: center;
        margin-top: 50px;
      }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
  <div class="container">
    <a class="navbar-brand" href="/app/index.php">Gestion Correction</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/app/index.php?controller=professeur&action=index">Professeurs</a></li>
        <li class="nav-item"><a class="nav-link" href="/app/index.php?controller=epreuve&action=index">Épreuves</a></li>
        <li class="nav-item"><a class="nav-link" href="/app/index.php?controller=correction&action=index">Corrections</a></li>
        <li class="nav-item"><a class="nav-link" href="/app/index.php?controller=etablissement&action=index">Établissements</a></li>
        <li class="nav-item"><a class="nav-link" href="/app/index.php?controller=examen&action=index">Examens</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
