<?php
namespace App\Controllers;

use App\Models\Professeur;

class ProfesseurController {
    protected Professeur $model;

    public function __construct() {
        $this->model = new Professeur();
    }

    // Liste tous les professeurs
    public function index() {
        $professeurs = $this->model->getAll();
        require_once __DIR__ . '/../views/professeur/index.php';
    }

    // Formulaire + traitement création
    public function new() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->formArray($_POST);
            $this->model->save();
            header('Location: ?controller=professeur&action=index');
            exit;
        }
        $professeur = null;
        require_once __DIR__ . '/../views/professeur/form.php';
    }

    // Formulaire + traitement modification
    public function edit() {
        if (!isset($_GET['id'])) {
            header('Location: ?controller=professeur&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->formArray($_POST);
            $this->model->update();
            header('Location: ?controller=professeur&action=index');
            exit;
        }

        $professeur = $this->model->getById((int)$_GET['id']);
        if (!$professeur) {
            header('Location: ?controller=professeur&action=index');
            exit;
        }
        require_once __DIR__ . '/../views/professeur/form.php';
    }

    // Supprime un professeur
    public function delete() {
        if (!isset($_GET['id'])) {
            header('Location: ?controller=professeur&action=index');
            exit;
        }
        $this->model->id = (int)$_GET['id'];
        $this->model->delete();
        header('Location: ?controller=professeur&action=index');
        exit;
    }
}
