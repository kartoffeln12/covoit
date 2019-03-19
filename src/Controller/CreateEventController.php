<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\SmtpConf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreateEventController extends AbstractController
{
    private $message = "    Bonjour
        Suite a la création d'un événement voici le lien pour la creation de participant: [link]
    
Ceci est un mail automatique merci de ne pas répondre";

    /**
     * @Route("/", name="create_event", methods="GET|POST")
     */
    public function index(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            $configs =(new SmtpConf())->config;
            if($event->getMailorga()) {
                $this->sendMail($event->getMailorga(), $configs["URL"].'/event/' . strval($event->getId()));
            }

            return $this->render('create_event/eventlink.html.twig', [
                'link' => $configs["URL"].'/event/'.strval($event->getId()),
            ]);

        }

        return $this->render('create_event/index.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    private function sendMail($email, $link) {
        $configs =(new SmtpConf())->config;
        $transport = (new \Swift_SmtpTransport($configs["smtp"]["host"], $configs["smtp"]["port"], $configs["smtp"]["encrypt"]))
            ->setUsername($configs["smtp"]["ident"])
            ->setPassword($configs["smtp"]["mdp"])
        ;
        $mailer = new \Swift_Mailer($transport);
        $this->message=str_replace('[link]',$link ,$this->message);
        $subject = 'Creation événement covoiturage';
		if(filter_var($email, FILTER_VALIDATE_EMAIL)!==false){
			$message = (new \Swift_Message($subject))
				->setFrom([$configs["smtp"]["ident"] => $configs["smtp"]["from"]])
				->setTo([$email])
				->setBody(
					$this->message
				)
			;
			$mailer->send($message);
		}
    }
}
