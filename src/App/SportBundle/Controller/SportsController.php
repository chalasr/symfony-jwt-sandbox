<?php

namespace App\SportBundle\Controller;

use App\SportBundle\Entity\Sport;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Sports REST Resource.
 *
 * @author Robin Chalas
 */
class SportsController extends Controller
{
    /**
     * Get sports.
     *
     * @Rest\Get("/sports")
     * @ApiDoc(
     *   section="Sport",
     * 	 resource=true,
     * 	 statusCodes={
     * 	   200="OK",
     * 	   401="Unauthorized"
     * 	 },
     * )
     *
     * @return $results User entities
     */
    public function getSportsListAction()
    {
        $em = $this->getEntityManager();

        $sports = $em->getRepository('AppSportBundle:Sport');
        $query = $sports->createQueryBuilder('s')
        ->select('s.id', 's.name', 's.isActive')
        ->getQuery();
        $results = $query->getResult();

        return $results;
    }

    /**
     * Create a new Sport entity.
     *
     * @Rest\Post("/sports")
     * @Rest\RequestParam(name="name", requirements="[^/]+", allowBlank=false, description="Name")
     * @Rest\RequestParam(name="isActive", requirements="true|false", nullable=true, description="is Active")
     * @Rest\View
     * @ApiDoc(
     *   section="Sport",
     * 	 resource=true,
     * 	 statusCodes={
     * 	   201="Created",
     * 	   400="Bad Request",
     * 	   401="Unauthorized",
     * 	 },
     * )
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return JsonResponse $response  Created Sport
     */
    public function createSportAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getEntityManager();

        $sport = new Sport();
        $sport->setName($paramFetcher->get('name'));
        $sport->setIsActive(false === $paramFetcher->get('isActive') ? false : true);

        $em->persist($sport);
        $em->flush();

        $response = array(
            'id'       => $sport->getId(),
            'name'     => $sport->getName(),
            'isActive' => $sport->getIsActive(),
        );

        return new JsonResponse($response, 201);
    }

    /**
     * Returns Entity Manager.
     *
     * @return EntityManager $entityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}
