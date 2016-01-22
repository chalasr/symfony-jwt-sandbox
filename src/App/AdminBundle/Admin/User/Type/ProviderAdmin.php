<?php

namespace App\AdminBundle\Admin\User\Type;

use App\AdminBundle\Admin\User\BaseUserAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ProviderAdmin extends BaseUserAdmin
{
    protected $baseRouteName = 'admin_app_user_provider';

    protected $baseRoutePattern = 'users/providers';

    public function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->add('providerInformation', 'sonata_type_admin', array(
                'by_reference' => false,
                'required'     => false,
            ), array(
                'edit'       => 'inline',
                'admin_code' => 'sonata.admin.provider_information',
            ))
        ;
    }

    public function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('providerInformation.name', null, array(
                'label' => 'Nom du provider',
            ))
        ;

        parent::configureDatagridFilters($filterMapper);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('providerInformation.name', null, [
                'label' => 'Nom du provider',
            ])
        ;

        parent::configureListFields($listMapper);
    }
}
