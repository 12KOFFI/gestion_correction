<?php
namespace App\Controllers;

use App\Models\Examen;

class ExamenController {
    protected Examen $model;

    public function __construct() {
        $this->model = new Examen();
    }

    // Liste tous les examens
    public function index() {
        $examens = $this->model->getAll();
        require_once __DIR__ . '/../views/examen/index.php';
    }

    // Affiche le formulaire et traite la création d'un examen
    public function new() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->formArray($_POST);
            $this->model->save();
            header('Location: ?controller=examen&action=index');
            exit;
        }
        // affichage formulaire vide
        $examen = null;
        require_once __DIR__ . '/../views/examen/form.php';
    }

    public function create() {
        return $this->new();
    }

    // Affiche le formulaire d'édition et traite la mise à jour
    public function edit() {
        if (!isset($_GET['id'])) {
            header('Location: ?controller=examen&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->formArray($_POST);
            $this->model->update();
            header('Location: ?controller=examen&action=index');
            exit;
        }

        $examen = $this->model->getById((int)$_GET['id']);
        if (!$examen) {
            header('Location: ?controller=examen&action=index');
            exit;
        }

        require_once __DIR__ . '/../views/examen/form.php';
    }

    // Supprime un examen par son ID
    public function delete() {
        if (!isset($_GET['id'])) {
            header('Location: ?controller=examen&action=index');
            exit;
        }
        $this->model->setId((int)$_GET['id']);
        $this->model->delete();
        header('Location: ?controller=examen&action=index');
        exit;
    }
}
