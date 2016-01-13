<?php

namespace App\SportBundle\Controller;

use App\SportBundle\Entity\Sport;
use App\Util\Controller\AbstractRestController as Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Sports resource.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
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
     * @return array
     */
    public function getSportsListAction()
    {
        $em = $this->getEntityManager();
        $entities = $em->getRepository('AppSportBundle:Sport')->findBy(['isActive' => 1]);
        $results = array();

        foreach ($entities as $entity) {
            $results[] = $entity->toArray();
        }

        return $results;
    }

    /**
     * Creates a new Sport entity.
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
     * @return JsonResponse
     */
    public function createSportAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppSportBundle:Sport');
        $sport = ['name' => $paramFetcher->get('name')];

        $repo->findOneByAndFail($sport);
        $sport['isActive'] = false === $paramFetcher->get('isActive') ? false : true;

        $sport = $repo::create($sport);

        // Use JsonResponse to specify status code.
        return new JsonResponse($sport->toArray(), 201);
    }

    /**
     * Get Icon image from Sport entity.
     *
     * @Rest\Get("/sports/{name}/icon")
     * @ApiDoc(
     *   section="Sport",
     * 	 resource=true,
     * 	 statusCodes={
     * 	   200="OK",
     * 	   401="Unauthorized"
     * 	 },
     * )
     *
     * @param int $id Sport entity
     *
     * @return Response
     */
    public function getIconBySportAction($name)
    {
        $em = $this->getEntityManager();
        $sport = $em
            ->getRepository('AppSportBundle:Sport')
            ->findOneByOrFail(['name' => $name])
        ;
        $iconName = $sport->getIcon();

        return $this->forward('AppAdminBundle:SportAdmin:showIcon', array(
            'name'           => $iconName,
            '_sonata_admin'  => 'sonata.admin.sports',
        ));
    }
}
