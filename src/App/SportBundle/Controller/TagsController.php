<?php

namespace App\SportBundle\Controller;

use App\SportBundle\Entity\Tag;
use App\Util\Controller\AbstractRestController as Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Manages Tags API collection.
 *
 * @author Pham Xuan Thuy <phamxuanthuy@sutunam.com>
 * @author Robin Chalas   <rchalas@sutunam.com>
 */
class TagsController extends Controller
{
    /**
     * Get Tags.
     *
     * @Rest\Get("/tags")
     * @ApiDoc(
     *   section="Tag",
     * 	 resource=true,
     * 	 statusCodes={
     * 	   200="OK",
     * 	   401="Unauthorized"
     * 	 },
     * )
     *
     * @return array
     */
    public function getTagsListAction()
    {
        $em = $this->getEntityManager();
        $entities = $em->getRepository('AppSportBundle:Tag')->findAll();

        return $entities;
    }

    /**
     * Create a new Tag entity.
     *
     * @Rest\Post("/tags")
     * @Rest\RequestParam(name="name", requirements="[^/]+", allowBlank=false, description="Name")
     * @Rest\View
     * @ApiDoc(
     *   section="Tag",
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
     * @return array
     */
    public function createTagAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppSportBundle:Tag');
        $name = $paramFetcher->get('name');

        $tag = ['name' => $name];
        $repo->findOneByAndFail($tag);

        return $repo->create($tag);
    }

    /**
     * Get Tag Json from Tag entity.
     *
     * @Rest\Get("/tags/{id}")
     * @ApiDoc(
     *   section="Tag",
     * 	 resource=true,
     * 	 statusCodes={
     * 	   200="OK",
     * 	   401="Unauthorized"
     * 	 },
     * )
     *
     * @param int $id Tag entity
     *
     * @return array
     */
    public function getTagAction($id)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository('AppSportBundle:Tag')->findOrFail($id);

        return $entity;
    }

    /**
     * Update Tag entity.
     *
     * @Rest\Patch("/tags/{id}")
     * @Rest\RequestParam(name="name", requirements="[^/]+", allowBlank=false, description="Name")
     * @ApiDoc(
     *   section="Tag",
     * 	 resource=true,
     * 	 statusCodes={
     * 	   200="OK",
     * 	   401="Unauthorized"
     * 	 },
     * )
     *
     * @param int          $id
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function updateTagAction($id, ParamFetcher $paramFetcher)
    {
        $repo = $this
            ->getEntityManager()
            ->getRepository('AppSportBundle:Tag')
        ;
        $entity = $repo->findOrFail($id);
        $name = $paramFetcher->get('name');

        if ($entity->getName() == $name) {
            return $entity;
        }

        $changes = ['name' => $name];
        $repo->findOneByAndFail($changes);
        $entity = $repo->update($entity, $changes);

        return $entity;
    }

    /**
     * Delete a Tag entity.
     *
     * @Rest\Delete("/tags/{id}")
     * @ApiDoc(
     *   section="Tag",
     * 	 resource=true,
     * 	 statusCodes={
     * 	   200="OK",
     * 	   401="Unauthorized"
     * 	 },
     * )
     *
     * @param int $id Tag entity
     *
     * @return JsonResponse
     */
    public function deleteTagAction($id)
    {
        $repo = $this
            ->getEntityManager()
            ->getRepository('AppSportBundle:Tag')
        ;

        $tag = $repo->findOrFail($id);
        $repo->delete($tag);

        return ['success' => true];
    }
}
