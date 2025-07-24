<?php
// Affichage des erreurs en dev
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Chargement de l'autoloader Composer
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

// Récupérer le paramètre url (ex: "professeur/edit" ou vide)
$url = $_GET['url'] ?? 'home/index';

// Découper l'URL en segments
$params = explode('/', $url);

// Construire le nom du contrôleur (ex: ProfesseurController)
$controller = ucfirst(strtolower($params[0])) . 'Controller';

// Action (méthode) par défaut = index
$action = $params[1] ?? 'index';

// Namespace complet du contrôleur
$controllerClass = "App\\Controllers\\$controller";

// Vérifier que le contrôleur existe
if (class_exists($controllerClass)) {
    $instance = new $controllerClass();

    // Vérifier que la méthode existe
    if (method_exists($instance, $action)) {
        $instance->$action();
    } else {
        http_response_code(404);
        echo "❌ Action '$action' introuvable dans $controllerClass.";
    }
} else {
    http_response_code(404);
    echo "❌ Contrôleur '$controllerClass' introuvable.";
}
