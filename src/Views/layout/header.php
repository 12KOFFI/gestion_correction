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
        padding-top: 80px; /* Augmentation de l'espace pour la navbar */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }
      footer {
        background-color: #f8f9fa;
        padding: 20px 0;
        text-align: center;
        margin-top: 50px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
      }
      .navbar {
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 0.8rem 0;
      }
      .navbar-brand {
        font-weight: 700;
        font-size: 1.5rem;
        color: #fff !important;
        transition: all 0.3s ease;
      }
      .navbar-brand:hover {
        transform: translateY(-2px);
      }
      .navbar-nav .nav-link {
        color: rgba(255,255,255,0.9) !important;
        font-weight: 500;
        padding: 0.5rem 1.2rem !important;
        margin: 0 0.2rem;
        border-radius: 4px;
        transition: all 0.3s ease;
        position: relative;
      }
      .navbar-nav .nav-link:hover {
        color: #fff !important;
        background-color: rgba(255,255,255,0.15);
        transform: translateY(-2px);
      }
      .navbar-nav .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background-color: #fff;
        transition: all 0.3s ease;
        transform: translateX(-50%);
      }
      .navbar-nav .nav-link:hover::after {
        width: 60%;
      }
      .navbar-toggler {
        border: none;
        padding: 0.5rem;
      }
      .navbar-toggler:focus {
        box-shadow: 0 0 0 0.15rem rgba(255,255,255,0.5);
      }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
        <i class="bi bi-journal-check me-2"></i>
        <span>Gestion Correction</span>
      </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="/app/index.php">
            <i class="bi bi-house-door me-1"></i> Accueil
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/app/src/Views/etablissement/index.php">
            <i class="bi bi-person-video3 me-1"></i> Etablissements
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/app/src/Views/professeur/index.php">
            <i class="bi bi-person-video3 me-1"></i> Professeurs
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/app/src/Views/examen/index.php">
            <i class="bi bi-file-text me-1"></i> Examens
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/app/src/Views/epreuve/index.php">
            <i class="bi bi-journal-text me-1"></i> Épreuves
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/app/src/Views/correction/index.php">
            <i class="bi bi-check2-circle me-1"></i> Corrections
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
