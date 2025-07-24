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
        $etablissement = new Etablissement();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $etablissement->setNom($_POST['nom']);
            $etablissement->setVille($_POST['ville']);
            if ($etablissement->save()) {
                header('Location: index.php?controller=etablissement&action=index');
                exit;
            }
        }
        require_once __DIR__ . '/../views/etablissement/form.php';
    }

    // Formulaire + traitement modification
    public function edit() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?controller=etablissement&action=index');
            exit;
        }

        $etablissement = new Etablissement();
        $id = (int)$_GET['id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $etablissement->setId((int)$_POST['id']);
            $etablissement->setNom($_POST['nom']);
            $etablissement->setVille($_POST['ville']);
            if ($etablissement->update()) {
                header('Location: index.php?controller=etablissement&action=index');
                exit;
            }
        } else {
            $data = $etablissement->getById($id);
            if ($data) {
                $etablissement->setId($data['id']);
                $etablissement->setNom($data['nom']);
                $etablissement->setVille($data['ville']);
            } else {
                header('Location: index.php?controller=etablissement&action=index');
                exit;
            }
        }
        require_once __DIR__ . '/../views/etablissement/form.php';
    }

    // Supprime un établissement
    public function delete() {
        if (!isset($_GET['id'])) {
            header('Location: ?controller=etablissement&action=index');
            exit;
        }
        $this->model->setId((int)$_GET['id']);
        $this->model->delete();
        header('Location: ?controller=etablissement&action=index');
        exit;
    }
}
