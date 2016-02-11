<?php

namespace App\AdminBundle\Admin\Event\Type;

use App\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class CyclicAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'admin_app_events_cyclic';

    protected $baseRoutePattern = 'events/cylic';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('virtualDays', 'choice',  array(
                   'multiple' => true,
                   'label'    => 'Jours',
                   'choices'  => array(
                      'lundi'    => 'Lundi',
                      'mardi'    => 'Mardi',
                      'mercredi' => 'Mercredi',
                      'jeudi'    => 'Jeudi',
                      'vendredi' => 'Vendredi',
                      'samedi'   => 'Samedi',
                      'dimanche' => 'Dimanche',
                   )
            ))
            ->add('recurrence', null, array('label' => 'Durée'))
            ->add('repetition', null, array('label' => 'Repetition'))
        ;
    }
}
