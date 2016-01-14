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
<<<<<<< HEAD

=======
>>>>>>> 370d1f570040cdfe40cb61ebed45b01f4231f5a0
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
<<<<<<< HEAD
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
=======
     * @return array
     */
    public function createCategoryAction(ParamFetcher $paramFetcher)
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppSportBundle:Category');
        $name = $paramFetcher->get('name');
>>>>>>> 370d1f570040cdfe40cb61ebed45b01f4231f5a0

        $category = ['name' => $name];
        $repo->findOneByAndFail($category);

<<<<<<< HEAD
        return $result;
=======
        return $repo->create($category)->toArray();
>>>>>>> 370d1f570040cdfe40cb61ebed45b01f4231f5a0
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
<<<<<<< HEAD
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
=======
     * @return array
     */
    public function getCategoryAction($id)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository('AppSportBundle:Category')->findOrFail($id);

        return $entity->toArray();
>>>>>>> 370d1f570040cdfe40cb61ebed45b01f4231f5a0
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
<<<<<<< HEAD
     * @return array $response get Category
=======
     * @return array
>>>>>>> 370d1f570040cdfe40cb61ebed45b01f4231f5a0
     */
    public function updateCategoryAction($id, ParamFetcher $paramFetcher)
    {
<<<<<<< HEAD
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
=======
        $repo = $this
            ->getEntityManager()
            ->getRepository('AppSportBundle:Category')
        ;
        $entity = $repo->findOrFail($id);
        $name = $paramFetcher->get('name');

        if ($entity->getName() == $name) {
            return $entity->toArray();
        }
>>>>>>> 370d1f570040cdfe40cb61ebed45b01f4231f5a0

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
     * @return Doctrine\ORM\QueryBuilder $result
     */
    public function deleteCategoryAction($id)
    {
<<<<<<< HEAD

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
=======
        $repo = $this
            ->getEntityManager()
            ->getRepository('AppSportBundle:Category')
        ;

        $category = $repo->findOrFail($id);
        $repo->delete($category);
>>>>>>> 370d1f570040cdfe40cb61ebed45b01f4231f5a0

        return ['success' => true];
    }
}
