<?php

namespace App\AdminBundle\Admin\Event;

use App\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class AbstractEventAdmin extends AbstractAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'Nom de l\'évènement'));
        // - id
        // - title (NOM DE L'ÉVÉNEMENT)
        // - idSport (SPORT) 1 seul sport par event
        // - idUser (USER) 1 seul user créé l'event (coach ou sportrooper)
        // - date
        // - time : (horaire)
        // - duration
        // - isCyclic : 1 or 0
        // - cyclicDays : les jours de la semaine
        // - cyclicRecurrence : listbox (1,2,3,4)
        // - cyclicRepetitions : listbox (1,2,3,4,5,6,7,8,9,10)
        // - price : (gratuit si 0)
        // - pictureEvent
        // - description : (textarea)
        // - location : (string pour adresse texte et latitude longitude ?)
        // - minParticipants (integer between 1 to 100)
        // - maxParticipants (integer between minParticipants to 200)
        // - isAutoCancellable : (1 or 0 - ANNULER L'ÉVÉNEMENT SI MINIMUM NON ATTEINT)
        // - timeCancellation : listbox (6, 12, 24, 48)
        // - isForKids : (1 or 0 - ACCESSIBLE AUX ENFANTS)
        // - minKidsAge : (integer between 1 and 17)
        // - maxKidsAge : (integer between minKidsAge and 18)
    }
}
