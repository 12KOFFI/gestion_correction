<?php

namespace App\Controllers;

use App\Models\Epreuve;

class EpreuveController {
    
    // Afficher toutes les épreuves
    public function index() {
        $epreuve = new Epreuve();
        $epreuves = $epreuve->getAll();
        require_once __DIR__ . '/../views/epreuve/index.php';
    }

    // Ajouter une nouvelle épreuve
    public function new() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $epreuve = new Epreuve();
            $epreuve->formArray($_POST);
            $epreuve->save();
            header('Location: ?controller=epreuve&action=index');
            exit;
        }
        $epreuve = null;
        require_once __DIR__ . '/../views/epreuve/form.php';
    }

    // Modifier une épreuve
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ?controller=epreuve&action=index');
            exit;
        }

        $epreuve = (new Epreuve())->getById($id);
        if (!$epreuve) {
            header('Location: ?controller=epreuve&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $epreuve->formArray($_POST);
            $epreuve->update();
            header('Location: ?controller=epreuve&action=index');
            exit;
        }

        require_once __DIR__ . '/../views/epreuve/form.php';
    }

    // Supprimer une épreuve
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $epreuve = new Epreuve();
            $epreuve->id = $id;
            $epreuve->delete();
        }
        header('Location: ?controller=epreuve&action=index');
        exit;
    }
}
