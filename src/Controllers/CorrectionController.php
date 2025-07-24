<?php
namespace App\Controllers;

use App\Models\Correction;
use App\Models\Professeur;
use App\Models\Epreuve;

class CorrectionController {
    protected Correction $model;

    public function __construct() {
        $this->model = new Correction();
    }

    // Liste toutes les corrections
    public function index() {
        $corrections = $this->model->getAllWithDetails(); // méthode personnalisée à créer dans le modèle
        require_once __DIR__ . '/../views/correction/index.php';
    }

    // Formulaire + traitement création
    public function new() {
        $professeurs = (new Professeur())->getAll();
        $epreuves = (new Epreuve())->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->formArray($_POST);
            $this->model->save();
            header('Location: ?controller=correction&action=index');
            exit;
        }
        $correction = null;
        require_once __DIR__ . '/../views/correction/form.php';
    }

    // Formulaire + traitement modification
    public function edit() {
        $professeurs = (new Professeur())->getAll();
        $epreuves = (new Epreuve())->getAll();

        if (!isset($_GET['id'])) {
            header('Location: ?controller=correction&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->formArray($_POST);
            $this->model->update();
            header('Location: ?controller=correction&action=index');
            exit;
        }

        $correction = $this->model->getById((int)$_GET['id']);
        if (!$correction) {
            header('Location: ?controller=correction&action=index');
            exit;
        }
        require_once __DIR__ . '/../views/correction/form.php';
    }

    // Supprime une correction
    public function delete() {
        if (!isset($_GET['id'])) {
            header('Location: ?controller=correction&action=index');
            exit;
        }
        $this->model->id = (int)$_GET['id'];
        $this->model->delete();
        header('Location: ?controller=correction&action=index');
        exit;
    }
}
