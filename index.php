<?php
namespace App;

use App\Controllers\ProfesseurController;
use App\Controllers\ExamenController;
use App\Controllers\EpreuveController;
use App\Controllers\EtablissementController;
use App\Controllers\CorrectionController;

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/src/';

    // Pour le namespace Config
    if (strncmp('Config\\', $class, strlen('Config\\')) === 0) {
        $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
        return;
    }

    // Pour le namespace App
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

switch($controller) {
    case 'home':
        require_once __DIR__ . '/src/Views/home.php/index.php';
        break;
    case 'professeur':
        $controller = new ProfesseurController();
        $controller->$action();
        break;
    case 'examen':
        $controller = new ExamenController();
        $controller->$action();
        break;
    case 'epreuve':
        $controller = new EpreuveController();
        $controller->$action();
        break;
    case 'etablissement':
        $controller = new EtablissementController();
        $controller->$action();
        break;
    case 'correction':
        $controller = new CorrectionController();
        $controller->$action();
        break;
    default:
        header("HTTP/1.0 404 Not Found");
        echo "Page non trouvée";
        break;
}
