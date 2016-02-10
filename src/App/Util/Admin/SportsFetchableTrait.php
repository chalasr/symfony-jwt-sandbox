<?php

namespace App\Util\Admin;

use Sonata\AdminBundle\Form\FormMapper;

/**
 * Makes an admin class able to fetch sports active and ordered.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
trait SportsFetchableTrait
{
    /**
     * Map sport's field to the current admin's formMapper.
     *
     * @param FormMapper $formMapper
     */
    protected function mapSports(FormMapper $formMapper)
    {
        $formMapper->add('sport', 'sonata_type_model', array(
            'required' => true,
            'query'    => $this->getSportsQuery(),
        ));
    }

    protected function getSportsQuery()
    {
        $em = $this->get('doctrine')->getManager();
        $query = $em->createQueryBuilder('s')
            ->select('s')
            ->from('AppSportBundle:Sport', 's')
            ->where('s.isActive = 1')
            ->orderBy('s.name', 'ASC');

        return $query;
    }
}
