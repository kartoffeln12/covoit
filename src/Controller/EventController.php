<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Participant;
use App\Form\ParticipantType;
use App\SmtpConf;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/event", name="event")
 */
class EventController extends AbstractController
{
    private $message = "    Bonjour
        Un Nouveau Participant s'est inscrit:
        Nom: [nom]
        Mail: [mail]
        Téléphone: [phone]
        Département: [dpt]
        Ville: [ville]
        Lieux de récupération possibles: [lieu]
        Statut: [status]
        
        Pour consulter la liste complète des participants inscrits pour ce covoiturage : [link]
    
Ceci est un mail automatique merci de ne pas répondre";
    /**
     * @Route("/{id}/", name="event")
     */
    public function index(Request $request, $id)
    {
        $event = $this->getDoctrine()->getManager()->getRepository(Event::class)->find($id);
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);
        $configs =(new SmtpConf())->config;
        if ($form->isSubmitted() && $form->isValid()) {
            if(!$participant->getConducteur()&&!$participant->getPassager()){
                return $this->render('event/index.html.twig', [
                    'controller_name' => $event->getName(),
                    'form' => $form->createView(),
                    'link'=> $configs["URL"]."/event/liste/".strval($event->getId()),
                    'error' => "veuillez renseigner au moins un role: Conducteur, Passager ou les deux"
                ]);
            }else if(filter_var($participant->getMail(), FILTER_VALIDATE_EMAIL)===false){
                return $this->render('event/index.html.twig', [
                    'controller_name' => $event->getName(),
                    'form' => $form->createView(),
                    'link'=> $configs["URL"]."/event/liste/".strval($event->getId()),
                    'error' => "veuillez renseigner un mail valide"
                ]);
            }
            $participants = $this->getDoctrine()->getManager()->getRepository(Participant::class)
                ->findBy( ['event' => $event]);
            $em = $this->getDoctrine()->getManager();
            $participant->setEvent($event);
            $em->persist($participant);
            $em->flush();
            foreach ($participants as $old){
                print_r($old->getConducteur());
                if(($old->getConducteur() && $old->getPassager())
                || ($old->getConducteur()&& $participant->getPassager())
                || ($old->getPassager() && $participant->getConducteur())){
                    $this->sendMail($old, $event, $participant);
                }
            }
            //return $this->redirect('http://ac.localhost/event/liste/'.strval($event->getId()));
            return $this->redirect($configs["URL"]."/event/liste/".$event->getId());
        }
        return $this->render('event/index.html.twig', [
            'controller_name' => $event->getName(),
            'form' => $form->createView(),
            'link'=> $configs["URL"]."/event/liste/".strval($event->getId())
        ]);
    }

    /**
     * @Route("/liste/{id}", name="liste")
     */
    public function liste(Request $request, $id){
        $configs =(new SmtpConf())->config;
        $event = $this->getDoctrine()->getManager()->getRepository(Event::class)->find($id);
        $participants = $this->getDoctrine()->getManager()->getRepository(Participant::class)
            ->findBy( ['event' => $event]);
        return $this->render("/event/liste.html.twig",[
        'EventName' => $event->getName(),
            'link'=> $configs["URL"]."/event/".strval($event->getId()),
            'participants'=>$participants
        ]);
    }

    private function sendMail($email, $event, Participant $newParticipant) {
        $configs =(new SmtpConf())->config;
        $transport = (new \Swift_SmtpTransport($configs["smtp"]["host"], $configs["smtp"]["port"], $configs["smtp"]["encrypt"]))
            ->setUsername($configs["smtp"]["ident"])
            ->setPassword($configs["smtp"]["mdp"])
        ;
        $mailer = new \Swift_Mailer($transport);
        $this->message=str_replace('[nom]',$newParticipant->getNom() ,$this->message);
        $this->message=str_replace('[mail]',$newParticipant->getMail() ,$this->message);
        if($newParticipant->getPhone()){
            $phone=$newParticipant->getPhone();
        }else{
            $phone = '';
        }
        $this->message=str_replace('[phone]',$phone ,$this->message);
        $this->message=str_replace('[dpt]',$newParticipant->getDepartement() ,$this->message);
        $this->message=str_replace('[ville]',$newParticipant->getVille() ,$this->message);
        $this->message=str_replace('[link]',$configs["URL"]."/event/liste/".strval($event->getId()),$this->message);
        if($newParticipant->getLieu()){
            $lieu=$newParticipant->getLieu();
        }else{
            $lieu = '';
        }
        $this->message=str_replace('[lieu]',$lieu ,$this->message);
        if($newParticipant->getConducteur() && $newParticipant->getPassager()){
            $status="Conducteur ou passager";
        }else if($newParticipant->getConducteur()){
            $status="conducteur";
        }else{
            $status="Passager";
        }
        $this->message=str_replace('[status]',$status ,$this->message);
        $subject = "Nouveau Covoitureur pour l'événement: ".$event->getName();
        if(filter_var($email->getMail(), FILTER_VALIDATE_EMAIL)!==false){
            $message = (new \Swift_Message($subject))
                ->setFrom([$configs["smtp"]["ident"] => $configs["smtp"]["from"]])
                ->setTo([$email->getMail()])
                ->setBody(
                    $this->message)
            ;

            $mailer->send($message);
        }
    }
}
