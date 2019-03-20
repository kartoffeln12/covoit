
# Readme Du projet Covoiturage
### requierement: php >=7.2.4, mySql >=5.7.21, composer >=1.7.3, yarn >=1.12.1 node >=8.9.0
Les versions sont données à titre indicatif, ce sont celles utilisées pour le développement.


# INSTALLATION:
créer une BDD vide et un user ayant les droits dessus, reporter les informations liées à cette BDD dans le fichier **.env**
en ligne de commande à la racine du projet : 
(s'assurer que la BDD est disponible au préalable) 


	composer install
	yarn install
	yarn encore production
	php bin/console make:migration
	php bin/console doctrine:migrations:migrate

Une fois cela fait l’installation est terminée. Si le service **HTTPD** ou le server **WAMP** tourne, l'application doit être disponible
# Mail
Pour l'envoi des mails, il faut renseigner des informations de SMTP valide dans le fichier de configuration: **./src/SmtpConf.php**
# URL
Pour que l'appli fonctionne, il faut mettre l'url public de l'application à jour dans **./src/SmtpConf.php**
# RGPD
Pour la suppression des données périmées il faut lancer la commande  

    php bin/console app:delete
 **WARNING: la commande supprime toute les données des événements passés, jour courant inclus!**

Pour rendre la suppression automatique il faut crée une tache planifié.
*linux*:

    0 0 23 * * root [project-path]/bin/console app:delete
**WARNING: la commande supprime toute les données des événements passés, jour courant inclus!**

*windows*:

    schtasks /create /tn delete /tr "php [project-path]/bin/console app:Delete" /sc daily /st 23:00 /ru "System"
**WARNING: la commande supprime toute les données des événements passés, jour courant inclus!**
