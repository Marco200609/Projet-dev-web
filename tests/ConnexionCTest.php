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

        $_POST['nom'] = 'Favreau';               
        $_POST['prenom'] = 'Gael';               
        $_POST['email'] = 'gael.test@cesi.fr';    
        $_POST['password'] = 'Password123!';     
        $_POST['role'] = '2';                    
        $_POST['premier_compte'] = '1';         

        $mockModel = $this->createMock(\App\Models\ConnexionInsM::class);
        
        $mockModel->expects($this->once())
                  ->method('set_id_user')
                  ->willReturn(1); 

        $controller = new ConnexionInsC($this->mockTemplateEngine, $mockModel);

        $result = $controller->form_inscription();

        $this->assertEquals('/entreprises/add', $result);
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

        $mockModel = $this->createMock(\App\Models\ConnexionInsM::class);
        
        $mockModel->expects($this->once())
                  ->method('set_id_user')
                  ->willReturn(1); 

        $controller = new ConnexionInsC($this->mockTemplateEngine, $mockModel);

        $result = $controller->form_inscription();

        $this->assertEquals('/CompteInscription/Attente?role=' . $_POST['role'], $result);
    }

    public function testFormInscriptionEntrprisePremierCompteErrorNom() 
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $_POST['nom'] = '';               
        $_POST['prenom'] = 'Gael';               
        $_POST['email'] = 'gael.test@cesi.fr';    
        $_POST['password'] = 'Password123!';     
        $_POST['role'] = '2';                    
        $_POST['premier_compte'] = '1';         

        $mockModel = $this->createMock(\App\Models\ConnexionInsM::class);
        
        $this->mockTemplateEngine->expects($this->once())
                  ->method('render')
                  ->with(
                    $this->equalTo('/Compte/InscriptionRechercheEntreprise.html.twig'),
                    $this->callback(function($settings) {
                    return $settings['errors'][0] === "Nom invalide";
                    })
                  )
                  ->willReturn('Page Nom Invalide'); 

        $controller = new ConnexionInsC($this->mockTemplateEngine, $mockModel);

        $result = $controller->form_inscription();

        $this->assertEquals('Page Nom Invalide', $result);
    }

    public function testFormInscriptionEntrpriseExistanteCompteErrorPrenom() 
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $_POST['nom'] = '';               
        $_POST['prenom'] = 'Gael';               
        $_POST['email'] = 'gael.test@cesi.fr';    
        $_POST['password'] = 'Password123!';     
        $_POST['role'] = '2';                    
        $_POST['premier_compte'] = '0';         

        $mockModel = $this->createMock(\App\Models\ConnexionInsM::class);
        
        $this->mockTemplateEngine->expects($this->once())
                  ->method('render')
                  ->with(
                    $this->equalTo('/Compte/InscriptionRechercheEntreprise.html.twig'),
                    $this->callback(function($settings) {
                    return $settings['errors'][0] === "Nom invalide";
                    })
                  )
                  ->willReturn('Page Nom Invalide'); 

        $controller = new ConnexionInsC($this->mockTemplateEngine, $mockModel);

        $result = $controller->form_inscription();

        $this->assertEquals('Page Nom Invalide', $result);
    }

    public function testFormInscriptionErrorCodeEntreprise() 
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $_POST['nom'] = 'Favreau';               
        $_POST['prenom'] = 'Gael';               
        $_POST['email'] = 'gael.test@cesi.fr';    
        $_POST['password'] = 'Password123!';     
        $_POST['role'] = '2';                    
        $_POST['premier_compte'] = '0'; 
        $_POST['codeEntreprise'] = 'MAUVAIS_CODE'; 

        $mockModel = $this->createMock(\App\Models\ConnexionInsM::class);
        $mockEntreprise = $this->createMock(\App\Models\EntrepriseM::class);
        $mockTwig = $this->createMock(\Twig\Environment::class);

        $mockEntreprise->expects($this->once())
                    ->method('get_code_entreprise')
                    ->with('MAUVAIS_CODE')
                    ->willReturn(false);

        $this->mockTemplateEngine->expects($this->once())
            ->method('render')
            ->with(
            $this->equalTo('/Compte/InscriptionRechercheEntreprise.html.twig'),
            $this->callback(function($settings) {
            return $settings['errors'][0] === "Code entreprise invalide";
            })
            )
            ->willReturn('Page Code Entreprise Invalide');

        $controller = new ConnexionInsC($this->mockTemplateEngine, $mockModel, $mockEntreprise);

        $result = $controller->form_inscription();

        $this->assertEquals('Page Code Entreprise Invalide', $result);
    }

    public function testFormInscriptionPiloteErrorEmail() 
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $_POST['nom'] = 'Favreau';               
        $_POST['prenom'] = 'Gael';               
        $_POST['email'] = 'gael.test@cesi';
        $_POST['password'] = 'Password123!';     
        $_POST['role'] = '3';

        $mockModel = $this->createMock(\App\Models\ConnexionInsM::class);
        
        $this->mockTemplateEngine->expects($this->once())
                ->method('render')
                ->with(
                $this->equalTo('/Compte/InscriptionPilote.html.twig'),
                $this->callback(function($settings) {
                return $settings['errors'][0] === "Email invalide";
                })
            )
            ->willReturn('Page Email Invalide'); 

        $controller = new ConnexionInsC($this->mockTemplateEngine, $mockModel);

        $result = $controller->form_inscription();

        $this->assertEquals('Page Email Invalide', $result);
    }
    
    public function testFormInscriptionEtudiantErrorTelMdpLinkedin() 
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $_POST['nom'] = 'Favreau';               
        $_POST['prenom'] = 'Gael';               
        $_POST['email'] = 'gael.test@cesi.fr';
        $_POST['password'] = '!'; 
        $_POST['telephone'] = '01782845484';    
        $_POST['role'] = '1';
        $_POST['linkedin'] = 'https://www.linkedin.com/gael-test';

        $mockModel = $this->createMock(\App\Models\ConnexionInsM::class);
        
        $this->mockTemplateEngine->expects($this->once())
                ->method('render')
                ->with(
                $this->equalTo('/Compte/InscriptionEtudiant.html.twig'),
                $this->callback(function($settings) {
                return $settings['errors'][0] === "Numéro de téléphone invalide" &&
                       $settings['errors'][1] === "Mot de passe invalide" &&
                       $settings['errors'][3] === "URL LinkedIn invalide";
                })
            )
            ->willReturn('Page Invalide'); 

        $controller = new ConnexionInsC($this->mockTemplateEngine, $mockModel);

        $result = $controller->form_inscription();

        $this->assertEquals('Page Invalide', $result);
    } 

    public function testFormConnexionSucces()
    {

        $_POST['email'] = 'gael.test@cesi.fr';
        $_POST['password'] = 'Password123!';
        
        $_SESSION = [];

        $mockModel = $this->createMock(\App\Models\ConnexionInsM::class);

        $userData = [
            'id_utilisateur' => 42,
            'id_permission' => 1
        ];

        $mockModel->expects($this->once()) // BIEN VÉRIFIER LES PARENTHÈSES ICI
            ->method('get_id_user')
            ->with(
                $this->equalTo('gael.test@cesi.fr'), 
                $this->equalTo('Password123!'), 
                $this->isNull()
            )
            ->willReturn([
                'id_utilisateur' => 42,
                'id_permission' => 1
            ]);

        $controller = new ConnexionInsC($this->mockTemplateEngine, $mockModel);

        // 3. Exécution
        $resultat = $controller->form_connexion();

        // 4. Vérifications
        $this->assertEquals("/", $resultat);
        $this->assertEquals(42, $_SESSION['id']);
        $this->assertEquals(1, $_SESSION['role']);
    }

    public function testFormConnexionEchec()
    {
        $_POST['email'] = 'mauvais@test.fr';
        $_POST['password'] = 'raté';
        $_SESSION = [];

        $mockModel = $this->createMock(\App\Models\ConnexionInsM::class);
        $mockTwig = $this->createMock(\Twig\Environment::class);

        $mockModel->method('get_id_user')->willReturn(null);

        $controller = new ConnexionInsC($mockTwig, $mockModel);
        $resultat = $controller->form_connexion();

        $this->assertEquals("/CompteConnexion", $resultat);
        $this->assertArrayNotHasKey('id', $_SESSION); 
    }

    /**
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    **/
    public function testFormDeconnexion()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['id'] = 42;

        $controller = new ConnexionInsC($this->mockTemplateEngine);
        
        $result = $controller->form_deconnexion();

        $this->assertEquals('/', $result);
        $this->assertArrayNotHasKey('id', $_SESSION); 
    }
}
?>