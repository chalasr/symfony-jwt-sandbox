<?php


namespace App\AdminBundle\Admin\User;

use App\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\UserBundle\Model\UserInterface;

class ProviderAdmin extends UserAdmin
{
    protected $baseRouteName = 'admin_app_user_provider';
    protected $baseRoutePattern = 'users/provider';

    public function configureFormFields(FormMapper $formMapper){
        parent::configureFormFields($formMapper);

        $formMapper
            // ->add('coachInformation', null, array());
            ->add('coachInformation', 'sonata_type_admin', array(
                'by_reference' => false,
                'required' => false,
            ),array(
                'edit' => 'inline',
                'admin_code' => 'sonata.admin.provider_information'
            ));

    }
    public function configureDatagridFilters(DatagridMapper $filterMapper){
        parent::configureDatagridFilters($filterMapper);

        $filterMapper
            ->add('providerInformation.name')
        ;
    }


    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper
            ->add('name', null, [
                'label' => 'Name',
            ])
        ;
    }

}
