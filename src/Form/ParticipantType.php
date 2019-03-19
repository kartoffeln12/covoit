<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',null, array("label"=> "NOM - Prénom*"))
            ->add('mail',null, array("label"=> "Mail*"))
            ->add('phone',null, array("label"=> "Téléphone"))
            ->add('departement', ChoiceType::class, array(
                'choices'  => array(
                    'Hérault' => "Hérault",
                    'Gard' => "Gard",
                    'Lozère' => "Lozère",
                    'Pyrénées orientales'=>'Pyrénées Orientales',
                    'Aude'=>'Aude'
                ), "label"=>"Département*"))
            ->add('ville',null, array("label"=> "Ville*"))
            ->add('lieu',null, array("label"=> "Lieux de récupération possibles"))
            ->add('conducteur', CheckboxType::class,array(
                'required' => false, "label"=>"Conducteur"
            ))
            ->add('passager', CheckboxType::class,array(
                'required' => false, "label"=>"Passager"
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
