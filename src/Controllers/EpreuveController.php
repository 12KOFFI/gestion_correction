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
        
        // Créer un objet Epreuve vide ou charger depuis la base de données
        $epreuveObj = new Epreuve();
        
        if ($id) {
            $epreuveData = $epreuveObj->getById($id);
            if ($epreuveData) {
                // Copier les données dans l'objet
                $epreuveObj->id = $epreuveData['id'];
                $epreuveObj->nom = $epreuveData['nom'];
                $epreuveObj->type = $epreuveData['type'];
                $epreuveObj->id_examen = $epreuveData['id_examen'];
            } else {
                header('Location: /epreuve');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Mettre à jour l'objet avec les données du formulaire
                $epreuveObj->nom = $_POST['nom'] ?? '';
                $epreuveObj->type = $_POST['type'] ?? '';
                $epreuveObj->id_examen = !empty($_POST['id_examen']) ? (int)$_POST['id_examen'] : null;

                if ($id) {
                    $epreuveObj->id = $id;
                    $success = $epreuveObj->update();
                } else {
                    $success = $epreuveObj->save();
                }

                if ($success) {
                    header('Location: /app?controller=epreuve&action=index');
                    exit;
                } else {
                    $error = "Une erreur est survenue lors de l'enregistrement";
                }
            } catch (RuntimeException $e) {
                $error = $e->getMessage();
            }
        }

        // Récupérer la liste des examens pour le select
        $examens = (new \App\Models\Examen())->getAll();
        
        // Ne passer l'objet à la vue que si on est en mode édition
        $epreuve = $id ? $epreuveObj : null;
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
        header('Location: /app?controller=epreuve&action=index');
        exit;
    }
    
    // Afficher le formulaire de création d'une nouvelle épreuve
    public function new() {
        $this->form();
    }
}