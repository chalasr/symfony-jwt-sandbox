<?php

namespace App\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use App\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;

class SportUserAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $em = $this->get('doctrine')->getManager();
        $query = $em->createQueryBuilder('s')
                ->select('s')
                ->from('AppSportBundle:Sport', 's')
                ->where('s.isActive = 1')
                ->orderBy('s.id', 'ASC');

        $formMapper
            ->add('sport', 'sonata_type_model', array(
                'required' => true,
                'query'    => $query,
            ))
            ->add('price')
        ;
    }
}
