<?php

namespace App\SportBundle\Controller;


use App\SportBundle\Entity\Category;
use App\SportBundle\Entity\Sport;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use App\Util\Controller\AbstractRestController as Controller;

/**
 * Categories resource.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
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
     * @return Doctrine\ORM\QueryBuilder $results
     */
    public  function getCategoriesListAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('AppSportBundle:Category')->findAll();
        $results = array();
        foreach ($entities as $entity) {

            $results[] = array('id'=>$entity->getId(),'name'=>$entity->getName());
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
     * @return Doctrine\ORM\QueryBuilder $result
     */
    public function createCategoryAction(ParamFetcher $paramFetcher)
    {
        $result=array();
        $em = $this->getDoctrine()->getEntityManager();

        $category = new Category();
        $category->setName($paramFetcher->get('name'));

        $exists = $em->getRepository('AppSportBundle:Category')->findBy(array(
            'name' => $category->getName()
        ));
        if(!$exists) {
            $em->persist($category);
            $em->flush();
            $result['id']=$category->getId();
            $result['name']=$category->getName();
        }


        return $result;
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
     *@return Doctrine\ORM\QueryBuilder $result
     */
    public function getCategoryAction($id)
    {
        $result=array();
        $em = $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppSportBundle:Category')->findOneBy(array('id'=>$id));
        if($entity){
            $result['id']=$entity->getId();
            $result['name']=$entity->getName();
        }
        return $result;
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

     * @param int $id Category entity
     * @param ParamFetcher $paramFetcher
     *
     * @return array $response get Category
     */
    public function updateCategoryAction($id,ParamFetcher $paramFetcher)
    {
        $response=array();
        $em = $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('AppSportBundle:Category')->findOneBy(array('id'=>$id));
        if($entity){
            $entity->setName($paramFetcher->get('name'));
            $response['id']=$entity->getId();
            $response['name']=$entity->getName();
        }
        return $response;
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
     * @return Doctrine\ORM\QueryBuilder $result
     */
    public function deleteCategoryAction($id)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $category = $em->getRepository('AppSportBundle:Category')->find($id);

        if ($category) {
            $em->remove($category);
            $em->flush();
        }
        $result=array(
            'id'=>$category->getId(),
            'name'=>$category->getName(),
        );

        return $result;

    }
}
