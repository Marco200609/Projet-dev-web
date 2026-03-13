<?php

/**
 * This is the router, the main entry point of the application.
 * It handles the routing and dispatches requests to the appropriate controller methods.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\EntrepriseC;


$loader = new \Twig\Loader\FilesystemLoader('../src/Views');
$twig = new \Twig\Environment($loader, [
    'debug' => true
]);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
        echo 'Welcome page';

        break;

    case '/entreprises':
        $controllerEnt = new EntrepriseC($twig);
        $controllerEnt->PageEntreprise();
        break;
    default:
        // TODO : return a 404 error
        echo '404 Not Found';
        break;
}