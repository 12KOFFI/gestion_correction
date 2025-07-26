<?php
// Chargement de l'autoloader Composer
require_once __DIR__ . '/../../../vendor/autoload.php';

// Importation des classes nécessaires
use App\Controllers\CorrectionController;

// Vérification de l'ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php?error=' . urlencode("ID de correction invalide"));
    exit();
}

$id = (int)$_GET['id'];
$correctionController = new CorrectionController();

try {
    // Récupération de la correction pour vérifier son existence
    $correction = $correctionController->getById($id);
    
    if (!$correction) {
        throw new Exception("La correction demandée n'existe pas.");
    }
    
    // Suppression de la correction
    $success = $correctionController->delete($id);
    
    if ($success) {
        header('Location: index.php?success=' . urlencode("La correction a été supprimée avec succès"));
    } else {
        throw new Exception("Une erreur est survenue lors de la suppression de la correction.");
    }
    
} catch (Exception $e) {
    header('Location: index.php?error=' . urlencode($e->getMessage()));
}

exit();
