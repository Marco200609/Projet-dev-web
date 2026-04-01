<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\AcceuilC;

class AccueilCTest extends TestCase
{

    private $mockTemplateEngine;

    protected function setUp(): void {
        $this->mockTemplateEngine = $this->createMock(\Twig\Environment::class);
    }

    public function testPageAcceuil()
    {
        $this->mockTemplateEngine->expects($this->once())
            ->method('render')
            ->with(
                $this->equalTo('Acceuil/Acceuil.html.twig'),
                $this->callback(function ($settings) {
                    return isset($settings['NbOffres']) && 
                           isset($settings['NbEntreprises']) && 
                           isset($settings['NbJuniors']) &&
                           isset($settings['liste_competences']) &&
                           isset($settings['liste_contrats']) &&
                           isset($settings['liste_domaines']);
                })
            )
            ->willReturn('Page Accueil');

        $controller = new AcceuilC($this->mockTemplateEngine);

        $output = $controller->PageAcceuil();

        $this->assertEquals('Page Accueil', $output);
    }

    public function testPageMentionsLegales()
    {
         $this->mockTemplateEngine->expects($this->once())
            ->method('render')
            ->with('Acceuil/MentionsLegales.html.twig')
            ->willReturn('Page Mentions Légales');

        $controller = new AcceuilC($this->mockTemplateEngine);

        $output = $controller->PageMentionsLegales();

        $this->assertEquals('Page Mentions Légales', $output);
    }
}