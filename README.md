<p align="center">
  <img width=auto height="203" alt="image" src="https://github.com/Marco200609/Projet-dev-web/blob/main/Public/Picture/LogoHead.png?raw=true" />
</p>

# Projet de Développement WEB

## Présentation du projet 

Notre mission est de produire entièrement un site web pour permettre aux étudiants, entreprises et pilotes d’avoir une communication plus facile.
Les étudiants pourront accéder à une mise en relation avec les entreprises et une recherche d’offres (alternance, stages) plus simplifiée. Ceux-ci doivent faire partie d’un groupe détenue par leur pilote.
Les pilotes doivent pouvoir créer des groupes distincts d’étudiants et d’accepter ou non de rendre visible des offres et des entreprises.
Les entreprises ayant un compte sur le site web peuvent poster des offres auxquels les étudiants pourront postuler et accepter ou non les potentielles candidatures.
Enfin, l’administrateur, possédant presque tous les droits, s’occupe d’accepter les différentes entreprises et pilotes ainsi que de supprimer des comptes potentiellement problématiques.
Lors de ce projet nous devions utiliser différents langages de programmation : HTML et CSS, du Javascript pour de la dynamisation côté client pour le Frontend puis du PHP pour le backend.

## Structure du Code

Pour notre site nous avons utiliser une architecture MVC. Le but de cette architecture est de séparer les différentes couches d’une application interactive pour simplifier la gestion de chaque et ainsi que cela soit plus lisible et débogage. Celle ci est composer de trois parties :
- les Modèles ayant les différentes focntionnalités de l'application, ils intéragissent directement avec la Bas De Données et peuvent appliquer une logique métier.

- Les Views qui font permettent de gérer l'interface visible par l'utilisateur, ici gérer avec le moteur de template Twig.

- Les Contrôlleurs faisan le lien entre Modèles Views afin de distribuer les bonnes données aux interfaces visible.

Nous avons aussi une connexion avec une Base de données pour pouvoir stockés les informations des différents utilisateurs et permettre le bon fonctionnement du site celon le rôle de chancun grâce à sa liaison avec notre architecture MVC.


## Utilisateurs

Notre site est prêt à accueilir quatre rôles distinct. 

- Des Entreprises qui seront la partie intégrante de notre site car ce sont elles qui proposeront des offres d'emplois aux Etudiants présent sur notre site.

- Des Pilotes qui sont des gérants de groupe d'étudiants afin de pouvoir suivre leur activité et validé les offres d'emplois proposé par les entreprise.

- Des Étudiants qui pourront directement candidater sur le site pour des offres un mail sera envoyé directement au entreprises grâce à une API.

- Des Admins qui seront là pour gérer l'ensemble des personnes présentes sur le site supprimer ou modifier les informations des comptes.



(le script de création de la base de données est disponible dans la branche BDD)

