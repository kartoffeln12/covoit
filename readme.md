#Readme Du projet Covoiturage
##requierement: php >=7.2.4, mySql >=5.7.21, composer >=1.7.3, yarn >=1.12.1 node >=8.9.0
Les versions sont données a titre indicatif, ce sont celles utilisées pour le developpement.

#INSTALATION:
crée une BDD vide et un user ayant les droit dessus, reporter les information lié a cette BDD dans le fichier .env

en ligne de commande a la racine du projet (s'assurer que la BDD est disponible au préalable):


	composer install
	yarn install
	yarn encore production
	php bin/console make:migration
	php bin/console doctrine:migrations:migrate

une fois cela fait l'instalation est finie. Si le service HTTPD ou le server WAMP tourne l'application doit etre disponible.
il est de la responsabilité de l'instalateur de parametrer wamp ou apache pour le projet.


Pour la suppression des données perimé il faut lancer la commande
php bin/console app:delete

#Mail
Pour l'envoi des mails il faut renseigner des information de SMTP valide dans le fichier de configuration: ./src/SmtpConf.php
##WARNING: la commande supprime toute les données des événements passés, jour courant inclus!

#URL
Pour que l'appli fonctionne il faut mettre l'url public de l'application a jour dans ./src/SmtpConf.php
Pour rendre la suppresion automatique il faut crée une tache planifié.
linux:
0 0 23 * * root [project-path]/bin/console app:delete
windows:
schtasks /create /tn delete /tr "php [project-path]/bin/console app:Delete" /sc daily /st 23:00 /ru "System"