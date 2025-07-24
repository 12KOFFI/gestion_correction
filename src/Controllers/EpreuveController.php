<?php

namespace App\Controllers;

use App\Models\Epreuve;
use RuntimeException;

class EpreuveController {
    
    // Afficher toutes les épreuves
    public function index() {
        $epreuveModel = new Epreuve();
        $epreuves = $epreuveModel->getAll(true); // true pour inclure les infos examen
        require_once __DIR__ . '/../views/epreuve/index.php';
    }

    // Afficher le formulaire d'ajout/modification
    public function form(int $id = null) {
        $epreuve = null;
        $error = null;
        
        if ($id) {
            $epreuve = (new Epreuve())->getById($id);
            if (!$epreuve) {
                header('Location: /epreuve');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $epreuve = $epreuve ?? new Epreuve();
                $epreuve->nom = $_POST['nom'] ?? '';
                $epreuve->type = $_POST['type'] ?? '';
                $epreuve->id_examen = !empty($_POST['id_examen']) ? (int)$_POST['id_examen'] : null;

                if ($id) {
                    $epreuve->id = $id;
                    $epreuve->update();
                } else {
                    $epreuve->save();
                }

                header('Location: /epreuve');
                exit;
            } catch (RuntimeException $e) {
                $error = $e->getMessage();
            }
        }

        // Récupérer la liste des examens pour le select
        $examens = (new \App\Models\Examen())->getAll();
        
        require_once __DIR__ . '/../views/epreuve/form.php';
    }

    // Supprimer une épreuve
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $epreuve = new Epreuve();
                $epreuve->id = (int)$id;
                $epreuve->delete();
            } catch (RuntimeException $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        header('Location: /epreuve');
        exit;
    }
    
    // Afficher le formulaire de création d'une nouvelle épreuve
    public function new() {
        $this->form();
    }
}