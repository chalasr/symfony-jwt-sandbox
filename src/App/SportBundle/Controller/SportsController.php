<?php

namespace App\SportBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Goutte\Client as HttpClient;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @ApiDoc(
     *   section="Sport",
     * 	 resource=true,
     * 	 statusCodes={
     * 	   200="OK (list all sports)",
     * 	   401="Unauthorized (require an access token)"
     * 	 },
     * )
     * @Rest\Get("/sports")
     *
     * @return $results User entities
     */
    public function getSportsListAction()
    {
        $em = $this->getEntityManager();

        $sports = $em->getRepository('AppSportBundle:Sport');
        $query = $sports->createQueryBuilder('s')
        ->select('s.id', 's.name')
        ->getQuery();
        $results = $query->getResult();

        return $results;
    }

    /**
     * Create a new Sport entity.
     *
     * @ApiDoc()
     * @Rest\Post("/sports")
     * @param  Request $request
     * @return $data
     */
    public function createSportAction(Request $request)
    {
        $em = $this->getEntityManager();
        $data = $request->request->all();
        // TODO Create the new sport.
        //
        return $data;
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
