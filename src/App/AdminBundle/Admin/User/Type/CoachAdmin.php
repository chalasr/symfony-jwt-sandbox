<?php

namespace App\AdminBundle\Admin\User\Type;

use App\AdminBundle\Admin\User\BaseUserAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CoachAdmin extends BaseUserAdmin
{
    protected $baseRouteName = 'admin_app_user_coach';

    protected $baseRoutePattern = 'users/coachs';

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
            ->with('Profil')
                ->add('firstname', null, array('required' => false, 'label' => 'Prénom'))
                ->add('lastname', null, array('required' => false, 'label' => 'Nom'))
                ->add('gender', 'sonata_user_gender', array(
                    'required'           => false,
                    'label'              => 'Sexe',
                    'translation_domain' => $this->getTranslationDomain(),
                ))
                ->add('dateOfBirth', 'sonata_type_date_picker', array(
                    'label'       => 'Date de naissance',
                    'format'      => 'dd/MM/yyyy',
                    'dp_language' => 'fr',
                    'required'    => false,
                ))
                ->add('file', 'file', $pictureOptions)
                ->add('description', 'textarea', array(
                    'attr' => array(
                        'maxlength' => 500,
                    ),
                    'required' => false,
                    'label'    => 'Description',
                ))
                ->add('phone', null, array('required' => false, 'label' => 'Téléphone'))
                ->add('address', 'textarea', array(
                    'label'    => 'Adresse',
                    'required' => false,
                    'attr'     => array(
                      'maxlength' => 500,
                    ),
                ))
                ->add('city', null, array(
                    'label'    => 'Ville',
                    'required' => false,
                ))
                ->add('zipcode', null, array(
                    'label'    => 'Code postal',
                    'required' => false,
                ))
            ->end()
            ->with('Sports')
                ->add('sportUsers', 'sonata_type_collection', array(
                    'by_reference' => false,
                    'required'     => false,
                    'label'        => false,
                ), array(
                    'edit'       => 'inline',
                    'inline'     => 'table',
                    'admin_code' => 'app_admin.admin.sport_user',
                ))
            ->end()
            ->with('Infos professionnelles')
                ->add('coachInformation', 'sonata_type_admin', array(
                    'by_reference' => false,
                    'required'     => false,
                    'label'        => false,
                ), array(
                    'edit' => 'inline',
                ))
            ->end()
            ->with('Documents')
                ->add('coachDocuments', 'sonata_type_collection', array(
                    'by_reference' => false,
                    'required'     => false,
                    'label'        => false,
                ), array(
                    'edit'       => 'inline',
                    'inline'     => 'table',
                    'admin_code' => 'sonata.admin.coach_document',
                ))
            ->end()
            ->with('Accès')
                ->add('email')
                ->add('plainPassword', 'password', array(
                    'label'    => 'Mot de passe',
                    'required' => (!$this->getSubject() || is_null($this->getSubject()->getId())),
                ))
            ->end()
        ;

        if ($this->getSubject() && !$this->getSubject()->hasRole('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->with('Gestion')
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
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('label' => 'Id'))
            ->addIdentifier('email')
            ->add('firstname', null, array('label' => 'Prénom'))
            ->add('lastname', null, array('label' => 'Nom'))
            ->add('phone', null, array('label' => 'Téléphone'))
            ->add('createdAt', 'date', array('label' => 'Créé le', 'format' => 'd/m/Y'))
            ->add('_action', 'actions', [
                'actions' => array(
                    'show'   => [],
                    'edit'   => [],
                    'delete' => [],
                ),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('id')
            ->add('email')
            ->add('lastname', null, array('label' => 'Nom'))
            ->add('group')
        ;
    }

    public function prePersist($object)
    {
        $uploadPath = $this->locateResource('@AppUserBundle/Resources/public/coach_documents');

        foreach ($object->getCoachDocuments() as $document) {
            $document->setUser($object);

            if ($document->getUrlFile()) {
                $document->uploadDocument($uploadPath);
            }
        }
    }

    public function preUpdate($object)
    {

        $uploadPath = $this->locateResource('@AppUserBundle/Resources/public/coach_documents');

        foreach ($object->getCoachDocuments() as $document) {
            $document->setUser($object);

            if ($document->getUrlFile()) {
                $document->uploadDocument($uploadPath);
            }
        }
    }
}
