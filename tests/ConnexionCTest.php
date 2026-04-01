<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\ConnexionInsC;

class ConnexionCTest extends TestCase
{

    private $mockTemplateEngine;

    protected function setUp(): void {
        $this->mockTemplateEngine = $this->createMock(\Twig\Environment::class);
    }

    public function testPageConnexion()
    {
        $this->mockTemplateEngine->expects($this->once())
            ->method('render')
            ->with('/Compte/Connexion.html.twig')
            ->willReturn('Page Connexion');

        $controller = new ConnexionInsC($this->mockTemplateEngine);

        $output = $controller->page_connexion();

        $this->assertEquals('Page Connexion', $output);
    }

    public function testPageInscription()
    {
        $this->mockTemplateEngine->expects($this->once())
            ->method('render')
            ->with('/Compte/ChoixInscription.html.twig')
            ->willReturn('Page Inscription');

        $controller = new ConnexionInsC($this->mockTemplateEngine);

        $output = $controller->page_inscription();

        $this->assertEquals('Page Inscription', $output);
    }   

    public function testPageInscriptionPilote()
    {
        $this->mockTemplateEngine->expects($this->once())
            ->method('render')
            ->with('/Compte/InscriptionPilote.html.twig')
            ->willReturn('Page Inscription Pilote');

        $controller = new ConnexionInsC($this->mockTemplateEngine);

        $output = $controller->page_inscription_pilote();

        $this->assertEquals('Page Inscription Pilote', $output);
    }

    public function testPageInscriptionEtudiant()
    {
        $this->mockTemplateEngine->expects($this->once())
            ->method('render')
            ->with('/Compte/InscriptionEtudiant.html.twig')
            ->willReturn('Page Inscription Etudiant');

        $controller = new ConnexionInsC($this->mockTemplateEngine);

        $output = $controller->page_inscription_etudiant();

        $this->assertEquals('Page Inscription Etudiant', $output);
    }

    public function testPageIntermediaireInscriptionEntreprise()
    {
        $this->mockTemplateEngine->expects($this->once())
            ->method('render')
            ->with('/Compte/ChoixIntermediaireEntreprise.html.twig')
            ->willReturn('Page Intermédiaire Inscription Entreprise');

        $controller = new ConnexionInsC($this->mockTemplateEngine);

        $output = $controller->pageIntermediaire_inscription_entreprise();

        $this->assertEquals('Page Intermédiaire Inscription Entreprise', $output);
    }

    public function testPageInscriptionRechercheEntreprise()
    {
        // Simule une URL contenant '/Ajouter' pour tester si la méthode trouve bien le premier compte
        $_SERVER['REQUEST_URI'] = '/Compte/Ajouter'; 

        $this->mockTemplateEngine->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo('/Compte/InscriptionRechercheEntreprise.html.twig'),
                $this->callback(function ($settings) {
                    return $settings['premier_compte'] === true;
                })
            )
            ->willReturn('Page Intermédiaire Recherche Entreprise');

        $controller = new ConnexionInsC($this->mockTemplateEngine);
        
        $result = $controller->page_inscription_recherche_entreprise();
        $this->assertEquals('Page Intermédiaire Recherche Entreprise', $result);
    }

    public function testPageInscriptionAdmin()
    {
        $this->mockTemplateEngine->expects($this->once())
            ->method('render')
            ->with('/Compte/InscriptionAdmin.html.twig')
            ->willReturn('Page Inscription Admin');

        $controller = new ConnexionInsC($this->mockTemplateEngine);

        $output = $controller->page_inscription_admin();

        $this->assertEquals('Page Inscription Admin', $output);
    }

    public function testPageInscriptionAttente()
    {
        $_GET['role'] = '1';

        $this->mockTemplateEngine->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo('/Compte/InscriptionAttente.html.twig'),
                $this->callback(function ($settings) {
                    return $settings['role'] === '1';
                })
            )
            ->willReturn('Page Inscription Attente');

        $controller = new ConnexionInsC($this->mockTemplateEngine);
        $result = $controller->page_inscription_attente();

        $this->assertEquals('Page Inscription Attente', $result);
    }
 
    public function testFormInscriptionEntrepriseInxistante() 
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $_POST['nom'] = 'Favreau';               
        $_POST['prenom'] = 'Gael';               
        $_POST['email'] = 'gael.test@cesi.fr';    
        $_POST['password'] = 'Password123!';     
        $_POST['role'] = '2';                    
        $_POST['premier_compte'] = '1';         

        $mockEntreprise = $this->createMock(\App\Models\EntrepriseM::class);
        $mockModel = $this->createMock(\App\Models\ConnexionInsM::class);
        
        $mockModel->expects($this->once())
                  ->method('set_id_user')
                  ->willReturn(1); 

        $controller = new ConnexionInsC($this->mockTemplateEngine, $mockModel);

        $resultat = $controller->form_inscription();

        $this->assertEquals('/entreprises/add', $resultat);
    }

    public function testFormInscriptionEntrepriseExistante() 
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $_POST['nom'] = 'Favreau';               
        $_POST['prenom'] = 'Gael';               
        $_POST['email'] = 'gael.test@cesi.fr';    
        $_POST['password'] = 'Password123!';     
        $_POST['role'] = '2';                    
        $_POST['premier_compte'] = '0';         

        $mockEntreprise = $this->createMock(\App\Models\EntrepriseM::class);
        $mockModel = $this->createMock(\App\Models\ConnexionInsM::class);
        
        $mockModel->expects($this->once())
                  ->method('set_id_user')
                  ->willReturn(1); 

        $controller = new ConnexionInsC($this->mockTemplateEngine, $mockModel);

        $resultat = $controller->form_inscription();

        $this->assertEquals('/CompteInscription/Attente?role=' . $_POST['role'], $resultat);
    }
}
?>