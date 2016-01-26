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
        $container = $this->getContainer();
        $roles = $container->getParameter('security.role_hierarchy.roles');
        $rolesChoices = self::flattenRoles($roles);
        /* Custom check displaying profile picture if is it */
        $pictureOptions =  array(
            'required'   => false,
            'data_class' => null,
            'label'      => 'Photo de profil',
        );
        if ($this->getSubject()->getId()) {
            $subject = $this->getSubject();
            if ($subject->getPicture()) {
                $path = sprintf('http://%s/bundles/appuser/pictures/%s', $container->getParameter('domain'), $subject->getPicture());
            } else {
                $path = sprintf('http://%s/bundles/appuser/pictures/default.jpg', $container->getParameter('domain'));
            }
            $pictureOptions['help'] = sprintf('<div class="icon_prev"><img src="%s"/></div>', $path);
        }
        /* End custom check */
        $formMapper
            ->with('Général')
                ->add('email')
                ->add('plainPassword', 'password', array(
                    'label' => 'Mot de passe',
                    'required' => (!$this->getSubject() || is_null($this->getSubject()->getId())),
                ))
            ->end()
            ->with('Profile')
                ->add('file', 'file', $pictureOptions)
                ->add('description', 'textarea', array(
                    'attr' => array(
                        'maxlength' => 500
                    ),
                    'required' => false,
                    'label'    => 'Déscription'
                ))
                ->add('phone', null, array('required' => false))
                ->add('address', 'textarea', array(
                    'label' => 'Adresse',
                    'required' => false,
                    'attr'    => array(
                      'maxlength' => 500
                    ),
                ))
                ->add('city', null, array('label' => 'Ville', 'required' => false))
                ->add('zipcode', null, array('label' => 'Code postal', 'required' => false))
            ->end()
        ;

        if ($this->getSubject() && !$this->getSubject()->hasRole('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->with('Management')
                    ->add('realRoles', 'choice', array(
                        'label'    => 'Rôles',
                        'choices'  => $rolesChoices,
                        'multiple' => true,
                        'required' => false,
                    ))
                    ->add('locked', null, array('required' => false))
                    ->add('enabled', null, array('required' => false))
                ->end()
            ;
        }

        $formMapper
            ->add('providerInformation', 'sonata_type_admin', array(
                'by_reference' => false,
                'required'     => false,
                'label'        => 'Informations du provider'
            ), array(
                'edit'       => 'inline',
                'admin_code' => 'sonata.admin.provider_information',
            ))
        ;
    }

    public function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('id')
            ->add('email')
            ->add('group')
            ->add('providerInformation.name', null, array(
                'label' => 'Nom du provider',
            ))
            ->add('zipcode')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('label' => 'Id'))
            ->addIdentifier('email')
            ->add('phone', null, array('label' => 'Téléphone'))
            ->add('createdAt', 'date', array('label' => 'Créé le', 'format' => 'd/m/Y'))
            ->add('_action', 'actions', [
                'actions' => array(
                    'edit'   => [],
                    'delete' => [],
                ),
            ])
        ;
    }
}
