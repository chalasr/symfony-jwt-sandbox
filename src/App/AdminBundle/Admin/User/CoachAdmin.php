<?php

namespace App\AdminBundle\Admin\User;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class CoachAdmin extends UserAdmin
{
    protected $baseRouteName = 'admin_app_user_coach';
    protected $baseRoutePattern = 'users/coach';

    public function configureFormFields(FormMapper $formMapper){
        parent::configureDatagridFilters($formMapper);
        $formMapper
            ->with('CoachInformation')
            ->add('proCardExpirationDate', null, array(
                'label' => '11',
            ))
            ->add('insuranceCompanyName', null, array(
                'label' => '222',
            ))
            ->add('insurancePolicyNumber', null, array(
                'label' => '334',
            ))
            ->add('insurancePolicyExpirationDate', null, array(
                'label' => '444',
            ));
    }
    public function configureDatagridFilters(DatagridMapper $filterMapper){
        parent::configureDatagridFilters($filterMapper);

        $filterMapper
            ->add('proCardExpirationDate')
            ->add('insuranceCompanyName')
            ->add('insurancePolicyNumber')
            ->add('insurancePolicyExpirationDate')
        ;
    }


    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper
            ->add('id', null, [
                'label' => '#',
            ])
            ->add('proCardExpirationDate', null, [
                'label' => 'proCardExpirationDate',
            ])
            ->add('insuranceCompanyName', null, [
                'label' => 'insuranceCompanyName',
            ])
            ->add('insurancePolicyNumber', null, [
                'label' => 'insurancePolicyNumber',
            ])
            ->add('insurancePolicyExpirationDate', null, [
                'label' => 'insurancePolicyExpirationDate',
            ])
            ->add('_action', 'actions', [
                'actions' => array(
                    'edit'   => [],
                    'delete' => [],
                ),
            ])
        ;
    }

}
