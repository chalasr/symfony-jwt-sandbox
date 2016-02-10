<?php

namespace App\AdminBundle\Admin\Sport;

use App\AdminBundle\Admin\AbstractAdmin;
use App\Util\Admin\SportsFetchableTrait as SportsFetchable;
use Sonata\AdminBundle\Form\FormMapper;

class SportUserAdmin extends AbstractAdmin
{
    use SportsFetchable;

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $this->mapSports($formMapper);

        $formMapper->add('price', null, array('label' => 'Prix'));
    }
}
