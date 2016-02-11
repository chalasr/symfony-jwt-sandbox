<?php

namespace App\AdminBundle\Admin\Event\Type;

use App\AdminBundle\Admin\Event\AbstractEventAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class SingleAdmin extends AbstractEventAdmin
{
    protected $baseRouteName = 'admin_app_events_single';

    protected $baseRoutePattern = 'events/single';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('event', 'sonata_type_admin', array(
                'by_reference' => false,
                'required'     => false,
                'label'        => false,
            ), array(
                'edit' => 'inline',
            ))
            ->add('time', 'time', array('label' => 'Horaire de début de l\'évènement', 'attr' => array('class' => 'fixed-time')))
            ->add('date', 'sonata_type_date_picker', array(), array(
                'format'      => 'dd/MM/yyyy',
                'dp_language' => 'fr',
                'widget'      => 'single_text',
                'attr'        => array(
                    'dp_language' => 'fr',
                    'class'       => 'datepicker',
                ),
            ))
            ->add('duration', null, array('label' => 'Durée'))
            ->add('cyclic')
            ->add('cyclicEvent', 'sonata_type_admin', array(
                'by_reference' => false,
                'required'     => false,
                'label'        => false,
            ), array(
                'edit' => 'inline',
            ))
            // Add cyclic event here + display it depending on cyclic value (false ? hidden : shown)
        ;
    }
}
