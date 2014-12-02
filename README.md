Todo rest API
===================

Première ébauche d'un travail sur la gestion d'une todo-list depuis une API REST.  
Temps approximatif passé sur ce travail : 8h.

----------

Pré-requis
-------------

> 
> - >= PHP 5.4
> - Serveur Apache avec (mod_rewrite) et un document root configuré vers le dossier `/web` de l'application.
> - composer 
> - CURL CLI 

Installation
-------------

> - Cloner le repo git
> - `composer install` à la racine
> - `mysql -u user -p password db_name < resources/todo.sql`

Have fun ;)
-------------
Les verbes HTTP suivants : GET, POST, PUT, DELETE sont disponibles à des routes bien spécifiques :

>GET  
http://yourdomain.com/api/v1/{usertoken}/todo/get

>POST  
http://yourdomain.com/api/v1/{usertoken}/todo/save

>PUT  
http://yourdomain.com/api/v1/{usertoken}/todo/update

>DELETE  
http://yourdomain.com/api/v1/{usertoken}/todo/delete

**NB :** On ne peut appeler une ressource qu'avec le bon verbe HTTP, au risque de se faire retourner une erreur 405 (Method not allowed).

Le token (gère un seul et même utilisateur) et est défini dans le fichier `resources/config.php` avec la clé `api.validtoken`. Si un mauvais token est passé, l'application renverra une erreur 403.

Les webservices peuvent être également appelés avec CURL depuis mon serveur distant.
Des fichiers de données sont disponibles dans le dossier `resources/data_client/`

>GET  
>`curl http://54.149.80.11/rest/web/api/v1/{token}/todo/get -s -w "\n"`

>POST  
>`curl -X POST http://54.149.80.11/rest/web/api/v1/{token}/todo/save --data-binary @path/to/resources/data_client/data_to_save.json -H 'Content-Type: application/json' -s -w "\n"`

>PUT  
>`curl -X PUT http://54.149.80.11/rest/web/api/v1/{token}/todo/update --data-binary @path/to/resources/data_client/data_to_update.json -H 'Content-Type: application/json' -s -w "\n"`

>DELETE  
>`curl -X PUT http://54.149.80.11/rest/web/api/v1/{token}/todo/update --data-binary @path/to/resources/data_client/data_to_delete.json -H 'Content-Type: application/json' -s -w "\n"`

Choix des solutions
--------------- 

1. *Le microframework [Silex](http://silex.sensiolabs.org/)* :  
 Il me permet de sécuriser mon application en bénéficiant du composant le plus important de cette application : [HttpFoundation](http://symfony.com/fr/doc/current/components/http_foundation/introduction.html).
C'est lui qui gère mes objets HTTP `Request` et `Response`.
Cette surcouche me permet d'avoir une application orientée objet, testable, sécurisé et normalisé (norme HTTP).
 Il me permet aussi de bénéficier d'un container (Pimple) pour l'injection de dépendance.   
Enfin, il me permet d'écrire du PHP lisible et élégant.
2. *Le format JSON* :  
Facilement et nativement interprété par PHP (`json_decode`). Très lisible et facilement compréhensible pour les développeurs et utilisateurs de l'API.
3. *PDO* :  
Encore une fois dans le but d'utiliser un composant natif de PHP. Cependant, si une autre extension de connexion  est souhaité, peu de changement seront nécessaire dans le code. Par exemple, l'objet `RestApp\Repository\TodoRepository` prend en argument un objet qui implémente l'interface `\RestApp\Service\Connection`
