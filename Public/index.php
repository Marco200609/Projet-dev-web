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

session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/') {
    echo 'Welcome page';

} elseif ($uri === '/entreprises') {
    $controllerEnt = new EntrepriseC($twig);
    $controllerEnt->PageEntreprise();

} elseif (preg_match('#^/detail_entreprise/(\d+)(/)?$#', $uri, $matches)) {
    $controllerEnt = new EntrepriseC($twig);
    $controllerEnt->PageDetailEntreprise($matches[1]);

} elseif ($uri === '/detail_entreprise/note') {
    $controllerEnt = new EntrepriseC($twig);
    $controllerEnt->FormAddNote();

} elseif ($uri === '/entreprises/add' || $uri === '/entreprises/add/') {
    $controllerEnt = new EntrepriseC($twig);
    $controllerEnt->PageAddEntreprise();

} elseif ($uri === '/entreprises/formadd' || $uri === '/entreprises/formadd/') {
    $controllerEnt = new EntrepriseC($twig);
    $controllerEnt->FormAddEntreprise();

} elseif (preg_match('#^/entreprises/update/(\d+)(/)?$#', $uri, $matches)) {
    $controllerEnt = new EntrepriseC($twig);
    $controllerEnt->PageUpdateEntreprise($matches[1]);

}elseif (preg_match('#^/entreprises/formupdate/(\d+)(/)?$#', $uri, $matches)) {
    $controllerEnt = new EntrepriseC($twig);
    $controllerEnt->FormUpdateEntreprise();
}



else {
    // 404
    echo '404 Not Found';
}