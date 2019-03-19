<?php
namespace App;

class SmtpConf
{
    public $config;
    public function __construct()
    {
        $this->config=array(

            'smtp' => array(
                "host" => "antivirus.ac-montpellier.fr",//URL SMTP
                "ident" => "quentin.lavigne@ac-montpellier.fr",//Identifiant Compte mail
                "mdp" => "", // mot de passe
                "port" => "25", // port
                "encrypt" => "", //encryption
                "from" => "Covoiturage AC Montpellier", //label de provenance
            ),
            "URL" => "http://ac.localhost" // URL PUBLIQUE DE L APPLICATION
        );
    }
}