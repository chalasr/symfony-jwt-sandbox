<?php


namespace App\AdminBundle\Admin\User;

use App\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\UserBundle\Model\UserInterface;

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
            ->add('coachInformation.proCardExpirationDate')
            ->add('coachInformation.insuranceCompanyName')
            ->add('coachInformation.insurancePolicyNumber')
            ->add('coachInformation.insurancePolicyExpirationDate')
        ;
    }


    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper
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
        ;
    }

}
