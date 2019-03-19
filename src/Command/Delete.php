<?php

namespace App\Command;


use App\Entity\Event;
use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Delete extends Command
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:Delete')
            ->setDescription('supprime les entrée perimée.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repositoryEvent = $this->em->getRepository(Event::class);
        $repositoryParticipant = $this->em->getRepository(Participant::class);
        $events=$repositoryEvent->findAll();
        foreach($events as $event){
            if($event->getDate()->getTimestamp ()< time()) {
                $participants = $repositoryParticipant->findBy(['event' => $event]);
                foreach ($participants as $participant) {
                    $this->em->remove($participant);
                    $this->em->flush();
                }
                $this->em->remove($event);
                $this->em->flush();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {

    }
}
