<?php
namespace App\Controllers;

use App\Models\Etablissement;

class EtablissementController {
    protected Etablissement $model;

    public function __construct() {
        $this->model = new Etablissement();
    }

    // Liste tous les établissements
    public function index() {
        $etablissements = $this->model->getAll();
        require_once __DIR__ . '/../views/etablissement/index.php';
    }

    // Formulaire + traitement création
    public function new() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->formArray($_POST);
            $this->model->save();
            header('Location: ?controller=etablissement&action=index');
            exit;
        }
        $etablissement = null;
        require_once __DIR__ . '/../views/etablissement/form.php';
    }

    // Formulaire + traitement modification
    public function edit() {
        if (!isset($_GET['id'])) {
            header('Location: ?controller=etablissement&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->formArray($_POST);
            $this->model->update();
            header('Location: ?controller=etablissement&action=index');
            exit;
        }

        $etablissement = $this->model->getById((int)$_GET['id']);
        if (!$etablissement) {
            header('Location: ?controller=etablissement&action=index');
            exit;
        }
        require_once __DIR__ . '/../views/etablissement/form.php';
    }

    // Supprime un établissement
    public function delete() {
        if (!isset($_GET['id'])) {
            header('Location: ?controller=etablissement&action=index');
            exit;
        }
        $this->model->id = (int)$_GET['id'];
        $this->model->delete();
        header('Location: ?controller=etablissement&action=index');
        exit;
    }
}
