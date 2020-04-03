<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminBookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'label' => "Date de début du séjour",
                'widget'=> 'single_text'
                ])
                
            ->add('endDate', DateType::class,[
                'label' => "Date de fin du séjour",
                'widget'=> 'single_text'
            ])
            ->add('comment',TextareaType::class,[
                'label' => "Commentaire du voyageur"
            ])
            ->add('booker', EntityType::class, [
                'label' => "Voyageur",
                'class' => User::class,
                'choice_label' => function($user){
                    return $user->getFirstName() ." ". strtoupper($user->getLastName());
                }
            ])
            ->add('ad', EntityType::class, [
                'label' => "Annonce de la réservation",
                'class' => Ad::class,
                'choice_label' => function($ad){
                    return "Annonce n° ". $ad->getId() . " - Titre : ". $ad->getTitle();
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
