<?php

use App\Controllers\AcceuilC;

/**
 * This is the router, the main entry point of the application.
 * It handles the routing and dispatches requests to the appropriate controller methods.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\EntrepriseC;

use function App\Services\mail;

$loader = new \Twig\Loader\FilesystemLoader('../src/Views');
$twig = new \Twig\Environment($loader, [
    'debug' => true,
    'cache' => false,
]);




session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/') {
    $controllerEnt = new AcceuilC($twig);
    echo $controllerEnt->PageAcceuil();

} elseif ($uri === '/MentionsLegales') {
    $controllerEnt = new AcceuilC($twig);
    echo $controllerEnt->PageMentionsLegales();

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

} elseif ($uri === '/offres') {
    $controllerOffre = new App\Controllers\OffreC($twig);
    $controllerOffre->PageOffres();
}

elseif ($uri ==='/CompteEtudiant') {
    $controllerConnexion = new App\Controllers\CompteEtudiantC($twig);
    $controllerConnexion -> PageCompteEtudiant();
} elseif ($uri ==='/CompteEntreprise') {
    $controllerConnexion = new App\Controllers\CompteEntrepriseC($twig);
    $controllerConnexion -> PageCompteEntreprise();
} elseif ($uri ==='/ComptePilote') {
    $controllerConnexion = new App\Controllers\ComptePiloteC($twig);
    $controllerConnexion -> PageComptePilote();
} elseif ($uri ==='/CompteAdmin') {
    $controllerConnexion = new App\Controllers\CompteAdminC($twig);
    $controllerConnexion -> PageCompteAdmin();
}

elseif ($uri ==='/CompteConnexion') {
    $controllerConnexion = new App\Controllers\ConnexionInsC($twig);
    echo $controllerConnexion -> page_connexion();

} elseif (preg_match('#^/detail_offre/(\d+)(/)?$#', $uri, $matches)) {
    $controllerOffre = new App\Controllers\OffreC($twig);
    $controllerOffre->PageDetailOffre($matches[1]);

} elseif ($uri === '/offres/add' || $uri === '/offres/add/') {
    $controllerOffre = new App\Controllers\OffreC($twig);
    $controllerOffre->PageFormAddOffre();

} elseif($uri === '/offres/formadd' || $uri === '/offres/formadd/') {
    $controllerOffre = new App\Controllers\OffreC($twig);
    $controllerOffre->FormAddOffre();

} elseif (preg_match('#^/offres/update/(\d+)(/)?$#', $uri, $matches)) {
    $controllerOffre = new App\Controllers\OffreC($twig);
    $controllerOffre->PageFormUpdateOffre($matches[1]);

} elseif (preg_match('#^/offres/formupdate/(\d+)(/)?$#', $uri, $matches)) {
    $controllerOffre = new App\Controllers\OffreC($twig);
    $controllerOffre->FormUpdateOffre($matches[1]);

} elseif (preg_match('#^/offres/postuler/(\d+)(/)?$#', $uri, $matches)) {
    $controllerCandidature = new App\Controllers\CandidatureC($twig);
    $controllerCandidature->PageCandidature($matches[1]);

} elseif ($uri === '/offres/formcandidature' || $uri === '/offres/formcandidature/') {
    $controllerCandidature = new App\Controllers\CandidatureC($twig);
    $controllerCandidature->FormAddCandidature();
}

//elseif ($uri.startsWith('https://CompteInscription')) {
//check si y a ça dans l'url (in machin) rentrer dans cette condition --> après check pour tel ou tel suite d'URL

elseif ($uri === '/CompteInscription'){
    $controllerInscription = new App\Controllers\ConnexionInsC($twig);
    echo $controllerInscription->page_inscription();
//    }
}

elseif ($uri==='/CompteInscription/Pilote') {
    $controllerInscription = new App\Controllers\ConnexionInsC($twig);
    echo $controllerInscription->page_inscription_pilote();
}

elseif ($uri === '/CompteInscription/Etudiant') {
    $controllerInscription = new App\Controllers\ConnexionInsC($twig);
    echo $controllerInscription->page_inscription_etudiant();
}

elseif ($uri === '/CompteInscription/Entreprise') {
//    renvoie à la page intermédiaire d'inscription du compte entreprise : soit un compte de l'entreprise existe déjà, soit aucun compte n'existe encore
    $controllerInscription = new App\Controllers\ConnexionInsC($twig);
    echo $controllerInscription->pageIntermediaire_inscription_entreprise();
}

//ici c la page twig qui va changer selon l'un ou l'autre (avec l'url de provenance) car il y a très peu qui change
elseif ($uri === '/CompteInscription/Entreprise/Recherche' || $uri === '/CompteInscription/Entreprise/Ajouter') {
    $controllerInscription = new App\Controllers\ConnexionInsC($twig);
    echo $controllerInscription->page_inscription_recherche_entreprise();
}

elseif ($uri === '/CompteInscription/Admin') {
    $controllerInscription = new App\Controllers\ConnexionInsC($twig);
    echo $controllerInscription->page_inscription_admin();
}

elseif ($uri =='/CompteInscription/Traitement'&& $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controllerInscription = new App\Controllers\ConnexionInsC($twig);
    echo $controllerInscription->form_inscription();
}

elseif ($uri === '/CompteConnexion/Traitement' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controllerConnexion = new App\Controllers\ConnexionInsC($twig);
    $redirection = $controllerConnexion->form_connexion();
    header("Location: " . $redirection);
}

elseif ($uri ==='/CompteInscription/Attente'){
    $controllerInscription = new App\Controllers\ConnexionInsC($twig);
    $redirection = $controllerInscription->form_inscription();
    header ("Location: ". $redirection);
}

elseif ($uri === '/deconnexion') {
    $controller = new App\Controllers\ConnexionInsC($twig);
    $redirection = $controllerConnexion->form_deconnexion();
    header("Location: " . $redirection);
}

elseif ($uri === '/VilleOffres') {
    $controllerVille = new App\Controllers\VilleC();
    $controllerVille->getVilleOffre();

} elseif ($uri === '/VilleEntreprises') {
    $controllerVille = new App\Controllers\VilleC();
    $controllerVille->getVilleEntreprise();

} elseif ($uri === '/EntreprisesOffres') {
    $controllerEntreprise = new App\Controllers\EntrepriseC($twig);
    $controllerEntreprise->getEntreprisesOffres();

} elseif ($uri === '/EntreprisesEntreprises') {
    $controllerEntreprise = new App\Controllers\EntrepriseC($twig);
    $controllerEntreprise->getEntreprisesEntreprises();

} elseif ($uri === '/changeWishlist') {
    $controllerCandidature = new App\Controllers\OffreC($twig);
    $controllerCandidature->changeWishlist();

} elseif ($uri === '/offres/togglepause' || $uri === '/offres/togglepause/') {
    $controllerOffre = new App\Controllers\OffreC($twig);
    $controllerOffre->ToggleOffrePause();

} elseif ($uri === '/offres/delete' || $uri === '/offres/delete/') {
$controllerOffre = new App\Controllers\OffreC($twig);
$controllerOffre->DeleteOffre();}


elseif (preg_match('#^/api/etudiants-groupe/(\d+)(/)?$#', $uri, $matches)) {
    $model = new \App\Models\ComptePiloteM();
    $etudiants = $model->getEtudiantsGroupe($matches[1]);
    header('Content-Type: application/json');
    echo json_encode($etudiants);
    exit;
}

elseif (preg_match('#^/admin/offre/delete/(\d+)(/)?$#', $uri, $matches)) {
    $model = new \App\Controllers\CompteAdminC($twig);
    $model->DeleteOffre($matches[1]);
}

elseif (preg_match('#^/admin/offre/accept/(\d+)(/)?$#', $uri, $matches)) {
    $model = new \App\Controllers\CompteAdminC($twig);
    $model->AcceptOffre($matches[1]);
}

elseif (preg_match('#^/admin/entreprise/delete/(\d+)(/)?$#', $uri, $matches)) {
    $model = new \App\Controllers\CompteAdminC($twig);
    $model->deleteEntreprise($matches[1]);
}

elseif (preg_match('#^/admin/entreprise/accept/(\d+)(/)?$#', $uri, $matches)) {
    $model = new \App\Controllers\CompteAdminC($twig);
    $model->AcceptEntreprise($matches[1]);
}

elseif (preg_match('#^/admin/pilote/delete/(\d+)(/)?$#', $uri, $matches)) {
    $model = new \App\Controllers\CompteAdminC($twig);
    $model->deletePilote($matches[1]);
}

elseif (preg_match('#^/admin/pilote/accept/(\d+)(/)?$#', $uri, $matches)) {
    $model = new \App\Controllers\CompteAdminC($twig);
    $model->AcceptPilote($matches[1]);
}

elseif ($uri === '/creer-groupe' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\ComptePiloteC($twig);
    $controller->creerGroupe();
}

else {
    // 404
    echo '404 Not Found';
}