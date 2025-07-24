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
            $this->model->setNom($_POST['nom']);
            $this->model->setPrenom($_POST['prenom']);
            $this->model->setGrade($_POST['grade']);
            $this->model->setEtablissementId($_POST['etablissement_id']);
            if ($this->model->save()) {
                header('Location: index.php?controller=professeur&action=index');
                exit;
            }
        }
        $professeur = $this->model;
        $etablissements = $this->model->getAllEtablissements();
        require_once __DIR__ . '/../views/professeur/form.php';
    }

    // Alias pour new()
    public function create() {
        return $this->new();
    }

    // Formulaire + traitement modification
    public function edit() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?controller=professeur&action=index');
            exit;
        }

        $id = (int)$_GET['id'];
        $data = $this->model->getById($id);
        
        if (!$data) {
            header('Location: index.php?controller=professeur&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->setId($id);
            $this->model->setNom($_POST['nom']);
            $this->model->setPrenom($_POST['prenom']);
            $this->model->setGrade($_POST['grade']);
            $this->model->setEtablissementId($_POST['etablissement_id']);
            if ($this->model->update()) {
                header('Location: index.php?controller=professeur&action=index');
                exit;
            }
        } else {
            // Accès aux propriétés de l'objet avec -> au lieu de ['']
            $this->model->setId($data->id);
            $this->model->setNom($data->nom);
            $this->model->setPrenom($data->prenom);
            $this->model->setGrade($data->grade);
            $this->model->setEtablissementId($data->id_etab); // Note: id_etab au lieu de etablissement_id
        }
        $professeur = $this->model;
        $etablissements = $this->model->getAllEtablissements();
        require_once __DIR__ . '/../views/professeur/form.php';
    }

    // Supprime un professeur
    public function delete() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?controller=professeur&action=index');
            exit;
        }
        $this->model->setId((int)$_GET['id']);
        $this->model->delete();
        header('Location: index.php?controller=professeur&action=index');
        exit;
    }
}
