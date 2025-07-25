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
    public function form() {
        // Activer l'affichage des erreurs
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        $error = null;
        
        // Récupérer l'ID depuis la requête GET
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        
        error_log('=== Début du formulaire ===');
        error_log('ID reçu: ' . ($id ?? 'null'));
        
        // Créer un objet Epreuve vide
        $epreuve = new Epreuve();
        
        // Si un ID est fourni, charger les données de l'épreuve
        if ($id) {
            error_log('Tentative de chargement de l\'épreuve avec ID: ' . $id);
            $epreuveData = $epreuve->getById($id);
            error_log('Données récupérées de la base de données: ' . print_r($epreuveData, true));
            
            if ($epreuveData) {
                // Créer un nouvel objet Epreuve avec les données chargées
                $epreuve = new Epreuve();
                $epreuve->id = $epreuveData['id'];
                $epreuve->nom = $epreuveData['nom'];
                $epreuve->type = $epreuveData['type'];
                $epreuve->id_examen = $epreuveData['id_examen'];
                
                error_log('Objet Epreuve créé: ' . print_r($epreuve, true));
                error_log('Valeurs des propriétés - id: ' . $epreuve->id . ', nom: ' . $epreuve->nom . ', type: ' . $epreuve->type);
            } else {
                error_log('Aucune donnée trouvée pour l\'ID: ' . $id);
                // Rediriger si l'épreuve n'existe pas
                header('Location: /app?controller=epreuve&action=index');
                exit;
            }
        } else {
            error_log('Aucun ID fourni, mode création');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Mettre à jour l'objet avec les données du formulaire
                $epreuve->nom = $_POST['nom'] ?? '';
                $epreuve->type = $_POST['type'] ?? '';
                $epreuve->id_examen = !empty($_POST['id_examen']) ? (int)$_POST['id_examen'] : null;

                if ($id) {
                    $epreuve->id = $id;
                    $success = $epreuve->update();
                } else {
                    $success = $epreuve->save();
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
        
        // Debug avant d'inclure la vue
        error_log("=== AVANT INCLUSION VUE ===");
        error_log("Objet epreuve: " . print_r($epreuve, true));
        error_log("Propriétés de l'objet epreuve:");
        error_log("- id: " . ($epreuve->id ?? 'null'));
        error_log("- nom: " . ($epreuve->nom ?? 'null'));
        error_log("- type: " . ($epreuve->type ?? 'null'));
        error_log("- id_examen: " . ($epreuve->id_examen ?? 'null'));
        
        // Inclure la vue avec les variables nécessaires
        $viewData = [
            'epreuve' => $epreuve,
            'examens' => $examens,
            'error' =>  $error
        ];
        
        // Debug des données transmises à la vue
        error_log("Données transmises à la vue: " . print_r($viewData, true));
        
        // Extraire les variables pour qu'elles soient disponibles dans la portée de la vue
        extract($viewData);
        
        // Debug après extraction
        error_log("=== APRÈS EXTRACTION ===");
        error_log("Variable epreuve existe: " . (isset($epreuve) ? 'oui' : 'non'));
        if (isset($epreuve)) {
            error_log("Type de epreuve: " . gettype($epreuve));
            error_log("Est un objet: " . (is_object($epreuve) ? 'oui' : 'non'));
            error_log("Classe de l'objet: " . get_class($epreuve));
        }
        
        // Inclure la vue
        error_log("Inclusion de la vue: " . __DIR__ . '/../views/epreuve/form.php');
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