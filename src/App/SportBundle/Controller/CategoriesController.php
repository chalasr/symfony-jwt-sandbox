<?php

namespace App\SportBundle\Controller;

use App\SportBundle\Entity\Category;
use App\Util\Controller\AbstractRestController as Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Categories resource.
 *
 * @author Pham Xuan Thuy <phamxuanthuy@sutunam.com>
 * @author Robin Chalas   <rchalas@sutunam.com>
 */
class CategoriesController extends Controller
{
    /**
     * Get Categories.
     *
     * @Rest\Get("/categories")
     * @ApiDoc(
     *   section="Category",
     * 	 resource=true,
     * 	 statusCodes={
     * 	   200="OK",
     * 	   401="Unauthorized"
     * 	 },
     * )
     *
     * @return array
     */
    public function getCategoriesListAction()
    {
        $em = $this->getEntityManager();
        $entities = $em->getRepository('AppSportBundle:Category')->findAll();
        $results = array();
        foreach ($entities as $entity) {
            $results[] = $entity->toArray();
        }

        return $results;
    }

    /**
     * Create a new Category entity.
     *
     * @Rest\Post("/categories")
     * @Rest\RequestParam(name="name", requirements="[^/]+", allowBlank=false, description="Name")
     * @Rest\View
     * @ApiDoc(
     *   section="Category",
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
    public function createCategoryAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppSportBundle:Category');
        $name = $paramFetcher->get('name');

        $category = ['name' => $name];
        $repo->findOneByAndFail($category);

        return $repo::create($category)->toArray();
    }

    /**
     * Get Category Json from Category entity.
     *
     * @Rest\Get("/categories/{id}")
     * @ApiDoc(
     *   section="Category",
     * 	 resource=true,
     * 	 statusCodes={
     * 	   200="OK",
     * 	   401="Unauthorized"
     * 	 },
     * )
     *
     * @param int $id Category entity
     *
     * @return array
     */
    public function getCategoryAction($id)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository('AppSportBundle:Category')->findOrFail($id);

        return $entity->toArray();
    }

    /**
     * update Category entity.
     *
     * @Rest\Patch("/categories/{id}")
     * @Rest\RequestParam(name="name", requirements="[^/]+", allowBlank=false, description="Name")
     * @ApiDoc(
     *   section="Category",
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
    public function updateCategoryAction($id, ParamFetcher $paramFetcher)
    {
        $repo = $this
            ->getEntityManager()
            ->getRepository('AppSportBundle:Category')
        ;
        $entity = $repo->findOrFail($id);
        $name = $paramFetcher->get('name');

        if ($entity->getName() == $name) {
            return $entity->toArray();
        }

        $changes = ['name' => $name];
        $repo->findOneByAndFail($changes);
        $entity = $repo->update($entity, $changes);

        return $entity->toArray();
    }

    /**
     * delete Category entity.
     *
     * @Rest\Delete("/categories/{id}")
     * @ApiDoc(
     *   section="Category",
     * 	 resource=true,
     * 	 statusCodes={
     * 	   200="OK",
     * 	   401="Unauthorized"
     * 	 },
     * )
     *
     * @param int $id Category entity
     *
     * @return JsonResponse $response get Category
     */
    public function deleteCategoryAction($id)
    {
        $repo = $this
            ->getEntityManager()
            ->getRepository('AppSportBundle:Category')
        ;

        $category = $repo->findOrFail($id);
        $repo::delete($category);

        return ['success' => true];
    }
}
