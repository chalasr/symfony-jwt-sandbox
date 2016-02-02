<?php

namespace App\UserBundle\Controller;

use App\SportBundle\Entity;
use App\UserBundle\Entity\User;
use App\Util\Controller\AbstractRestController as BaseController;
use App\Util\Controller\CanCheckPermissionsTrait as CanCheckPermissions;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpFoundation as Http;

/**
 * Users Controller.
 *
 * @author Pham Xuan Thuy <phamxuanthuy@sutunam.com>
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class UsersController extends BaseController
{
    use CanCheckPermissions;

    /**
     * List all users.
     *
     * @Rest\Get("/users")
     * @Rest\View(serializerGroups={"api"})
     *
     * @ApiDoc(
     *     section="User",
     *     resource=true,
     *     statusCodes={
     *         200="OK (list all users)",
     *         401="Unauthorized (this resource require an access token)"
     *     },
     * )
     *
     * @return Doctrine\ORM\QueryBuilder
     */
    public function getAllUsersAction()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppUserBundle:User');

        return $repo->findAll();
    }

    /**
     * Get a user by id.
     *
     * @Rest\Get("/users/{id}", requirements={"id" = "\d+"})
     * @Rest\View(serializerGroups={"api"})
     * @ApiDoc(
     *     section="User",
     *     resource=true,
     *     statusCodes={
     *         200="OK",
     *         401="Unauthorized (this resource require an access token)",
     *         404="User not found)"
     *     },
     * )
     *
     * @return array
     *
     * @throws NotFoundHttpException If the user does not exist
     */
    public function getUserAction($id)
    {
        return $this->findUserOrFail($id);
    }

    /**
     * Add a follower from current user to another.
     *
     * @Rest\Post("/users/followers/{follower}", requirements={"follower" = "\d+"})
     * @ApiDoc(
     *    section="User",
     *    resource=true,
     *    parameters={
     *     {"name"="follower", "dataType"="integer", "required"=true, "description"="Follower"}
     *   },
     *     statusCodes={
     *       204="No Content (follower successfully added)",
     *       401="Unauthorized (this resource require an access token)",
     *       422="Unprocessable Entity (self-following in forbidden|The user is already in followers)"
     *     },
     * )
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function addFollowerAction($follower)
    {
        $em = $this->getEntityManager();
        $user = $this->getCurrentUser();
        $follower = $this->findUserOrFail($follower);

        if (true === $this->iscurrentUser($follower)) {
            throw new UnprocessableEntityHttpException('Un utilisateur ne peut pas se suivre lui même');
        }

        if (true === $user->hasFollower($follower)) {
            throw new UnprocessableEntityHttpException('Cet utilisateur vous suit déjà');
        }

        $user->addFollower($follower);
        $em->flush();

        return $this->handleView(204);
    }

    /**
     * Remove a follower user from the current user.
     *
     * @Rest\Delete("/users/followers/{follower}", requirements={"follower" = "\d+"})
     * @ApiDoc(
     *    section="User",
     *    resource=true,
     *    parameters={
     *     {"name"="follower", "dataType"="integer", "required"=true, "description"="Follower"}
     *   },
     *     statusCodes={
     *       204="No Content (follower successfully deleted)",
     *       401="Unauthorized (this resource require an access token)",
     *       422="Follow does not exist"
     *     },
     * )
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     *
     * @throws UnprocessableEntityHttpException If the association does not exist
     */
    public function removeFollowerAction($follower)
    {
        $em = $this->getEntityManager();
        $user = $this->getCurrentUser();
        $follower = $this->findUserOrFail($follower);

        if (false === $user->hasFollower($follower)) {
            throw new UnprocessableEntityHttpException('Vous ne suivez pas cet utilisateur');
        }

        $user->removeFollower($follower);

        $em->flush();

        return $this->handleView(204);
    }

    /**
     * Get the current user.
     *
     * @Rest\Get("/users/current")
     * @Rest\View(serializerGroups={"api"})
     * @ApiDoc(
     *     section="User",
     *     resource=true,
     *     statusCodes={
     *         200="OK",
     *         401="Unauthorized (this resource require an access token)",
     *         422="Unprocessable Entity (self-following in forbidden|The user is already in followers)"
     *     },
     * )
     *
     * @return array
     *
     * @throws NotFoundHttpException If the user does not exist
     */
    public function getCurrentUserAction()
    {
        $user = $this->getCurrentUser();

        return $this->getUserAction($user->getId());
    }

    /**
     * Add a followed user to the current user.
     *
     * @Rest\Post("/users/follows/{followed}", requirements={"followed" = "\d+"})
     * @ApiDoc(
     *    section="User",
     *    resource=true,
     *    parameters={
     *     {"name"="followed", "dataType"="integer", "required"=true, "description"="Followed"}
     *   },
     *     statusCodes={
     *       204="No Content (follow successfully added)",
     *       401="Unauthorized (this resource require an access token)"
     *     },
     * )
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function addFollowedAction($followed)
    {
        $em = $this->getEntityManager();
        $user = $this->getCurrentUser();
        $followed = $this->findUserOrFail($followed);

        if (true === $this->iscurrentUser($followed)) {
            throw new UnprocessableEntityHttpException('Un utilisateur ne peut pas se suivre lui même');
        }

        if (true === $user->hasFollow($followed)) {
            throw new UnprocessableEntityHttpException('Vous suivez déjà cet utilisateur');
        }

        $user->addFollow($followed);

        $em->flush();

        return $this->handleView(204);
    }

    /**
     * Remove a followed user from the current user.
     *
     * @Rest\Delete("/users/follows/{followed}", requirements={"followed" = "\d+"})
     * @ApiDoc(
     *    section="User",
     *    resource=true,
     *    parameters={
     *     {"name"="followed", "dataType"="integer", "required"=true, "description"="Follow"}
     *   },
     *     statusCodes={
     *       204="No Content (follow successfully deleted)",
     *       401="Unauthorized (this resource require an access token)",
     *       422="Unprocessable Entity (User not followed yet)"
     *     },
     * )
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     *
     * @throws UnprocessableEntityHttpException If the association does not exist
     */
    public function removeFollowedAction($followed)
    {
        $em = $this->getEntityManager();
        $user = $this->getCurrentUser();
        $followed = $this->findUserOrFail($followed);

        if (false === $user->hasFollow($followed)) {
            throw new UnprocessableEntityHttpException('Vous ne suivez pas cet utilisateur');
        }

        $user->removeFollow($followed);

        $em->flush();

        return $this->handleView(204);
    }

    /**
     * Get the followers list of a given user.
     *
     * @Rest\Get("/users/{id}/followers")
     * @ApiDoc(
     *     section="User",
     *     resource=true,
     *     statusCodes={
     *         200="OK (list all followers)",
     *         401="Unauthorized (this resource require an access token)",
     *         404="User not found"
     *     },
     * )
     *
     * @return object
     */
    public function getFollowers($id)
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppUserBundle:User');
        $user = $this->findUserOrFail($id);

        return $user->getFollowers();
    }

    /**
     * Get the followings list of a given user.
     *
     * @Rest\Get("/users/{id}/follows")
     * @ApiDoc(
     *     section="User",
     *     resource=true,
     *     statusCodes={
     *         200="OK (list all followers)",
     *         401="Unauthorized (this resource require an access token)",
     *         404="User not found"
     *     },
     * )
     *
     * @return object
     */
    public function getFollows($id)
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppUserBundle:User');
        $user = $this->findUserOrFail($id);

        return $user->getFollows();
    }

    /**
     * Get a user.
     *
     * @param int $id
     *
     * @return User
     *
     * @throws NotFoundHttpException If the User does not exists
     */
    protected function findUserOrFail($id)
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppUserBundle:User');
        $user = $repo->find($id);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('Unable to find user with id %d', $id));
        }

        return $user;
    }

    /**
     * Update the picture of a given user.
     *
     * @Rest\Post("/users/{id}/picture", requirements={"id" = "\d+"})
     * @ApiDoc(
     *    section="User",
     *    resource=true,
     *     statusCodes={
     *       204="No Content (picture successfully updated)",
     *       401="Unauthorized (this resource require an access token)"
     *     }
     * )
     *
     * @param Request $request
     *
     * @return array
     */
    public function updatePicture($id, Request $request)
    {
        $user = $this->findUserOrFail($id);

        if (!$this->isCurrentUserId($id) && !$this->isAdmin()) {
            throw new AccessDeniedHttpException('This resource is only accessible by the user or an administrator');
        }

        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppUserBundle:User');

        $picture = $request->files->get('file');

        if (!$picture) {
            throw new UnprocessableEntityHttpException('The file parameter is missing');
        }

        $user->setFile($picture);

        $uploadPath = $this->locateResource('@AppUserBundle/Resources/public/pictures');

        if ($user->getFile()) {
            $user->uploadPicture($uploadPath);
            $em->persist($user);
            $em->flush();
        }

        return $user;
    }

    /**
     * Get the picture from a given user.
     *
     * @Rest\Get("/users/{id}/picture", requirements={"id" = "\d+"})
     * @ApiDoc(
     *    section="User",
     *    resource=true,
     *     statusCodes={
     *       204="No Content (picture successfully updated)",
     *       401="Unauthorized (this resource require an access token)"
     *     }
     * )
     *
     * @param Request $request
     *
     * @return array
     */
    public function getPicture($id, Request $request)
    {
        $user = $this->findUserOrFail($id);

        $path_picture = $this->locateResource('@AppUserBundle/Resources/public/pictures/' . $user->getPicture());
        $iconInfo = pathinfo($path_picture);

        if (false === isset($iconInfo['extension'])) {
            $path = $this->locateResource('@AppUserBundle/Resources/public/pictures/default.png');
        }

        $response = new Http\Response();
        $response->headers->set('Content-type', mime_content_type($path_picture));
        $response->headers->set('Content-length', filesize($path_picture));
        $response->sendHeaders();
        $response->setContent(file_get_contents($path_picture));
        return $response;
    }

    /**
     * List all sports from a given user.
     *
     *
     * @Rest\Get("/users/{id}/sports", requirements={"id" = "\d+"})
     * @ApiDoc(
     *     section="User",
     *     resource=true,
     *     statusCodes={
     *         200="OK (list all followers)",
     *         401="Unauthorized (this resource require an access token)",
     *         404="User not found"
     *     },
     * )
     *
     * @return array
     */
    public function getSports($id)
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppUserBundle:User');
        $user = $this->findUserOrFail($id);

        return $user->getFullSports();
    }

    /**
     * Add a sport to a given user.
     *
     * @Rest\Post("/users/{id}/sports", requirements={"id" = "\d+"})
     * @Rest\RequestParam(name="sport_id", requirements="\d+",description="sport")
     * @ApiDoc(
     *     section="User",
     *     resource=true,
     *     statusCodes={
     *         204="No content (success)",
     *         401="Unauthorized (this resource require an access token)",
     *         404="User not found"
     *     },
     * )
     *
     * @param int $id
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */

    public function addSport($id, ParamFetcher $paramFetcher)
    {
        $sportId = $paramFetcher->get('sport_id');

        #get user
        $user = $this->findUserOrFail($id);

        if (!$this->isCurrentUserId($id) && !$this->isAdmin()) {
            throw new AccessDeniedHttpException('This resource is only accessible by the user or an administrator');
        }

        #get sport
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppSportBundle:Sport');
        $sport = $repo->findOrFail($sportId);
        #set and update sportuser
        $su = $em->getRepository('AppSportBundle:SportUser')->findOneByAndFail(array('user' => $id, 'sport' => $sportId));

        $sportUser = new Entity\SportUser();
        $sportUser->setUser($user);
        $sportUser->setSport($sport);

        $em->persist($sportUser);
        $em->flush();

        return $this->handleView(204);
    }

    /**
     * Remove sport from a given user.
     *
     * @Rest\Delete("/users/{id}/sports", requirements={"id" = "\d+"})
     * @Rest\RequestParam(name="sport_id", requirements="\d+",description="sport")
     * @ApiDoc(
     * 	 section="User",
     * 	 resource=true,
     * 	 statusCodes={
     * 	     200="OK (list all followers)",
     * 	     401="Unauthorized (this resource require an access token)",
     * 	     404="User not found",
     * 	     403="Forbidden (Only the user or an admin can access this resource)"
     * 	 },
     * )
     *
     * @param int $id
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function removeSport($id, ParamFetcher $paramFetcher)
    {
        $this->findUserOrFail($id);

        if (!$this->isCurrentUserId($id) && !$this->isAdmin()) {
            throw new AccessDeniedHttpException('This resource is only accessible by the user or an administrator');
        }

        $sport_id = $paramFetcher->get('sport_id');
        $em = $this->getEntityManager();
        $sportUsers = $em->getRepository('AppSportBundle:SportUser')->findBy(array('user' => $id, 'sport' => $sport_id));

        if (!$sportUsers) {
            throw new NotFoundHttpException(sprintf('Unable to find sport %d with user %d', $sport_id, $id));
        }
        foreach ($sportUsers as $sportUser) {
            $em->remove($sportUser);
            $em->flush();
        }

        return $sportUser;
    }

    /**
     * search user
     *
     * @Rest\Post("/users/search")
     * @Rest\RequestParam(name="name",nullable=true, description="user's name")
     * @Rest\RequestParam(name="sports",nullable=true, description="sports name")
     * @Rest\RequestParam(name="groups",nullable=true, description="groups name")
     * @ApiDoc(
     *     section="User",
     *     resource=true,
     *     statusCodes={
     *         204="No content (success)",
     *         401="Unauthorized (this resource require an access token)",
     *         404="User not found"
     *     },
     * )
     *
     * @param Request $request
     *
     * @return array
     */
    public function userSearch(Request $request)
    {
        $name = $request->request->get('name');
        $sports = $request->request->get('sports');
        $groups = $request->request->get('groups');


        $qb = $this->getEntityManager()->createQueryBuilder();

        $query = $qb->select('U')
            ->from('AppUserBundle:User', 'U')
            ->JOIN('U.group', 'G')
            ->JOIN('U.sportUsers', 'SU')
            ->JOIN('AppSportBundle:Sport', 'S');
        if ($name) {
            $query->Where('U.firstname LIKE :firstname')
                ->orWhere('U.lastname LIKE :lastname')
                ->setParameter('firstname', '%' . $name . '%')
                ->setParameter('lastname', '%' . $name . '%');
        }
        if ($sports) {
            if(is_array($sports)){
                foreach ($sports as &$value) $value = "'".$value."'";
                unset($value);
            }else{
                $sports="'{$sports}'";
            }
            $query->andWhere("S.name IN (" . implode(',', $sports) . ")");
        }
        if ($groups) {
            if(is_array($groups)){
                foreach ($groups as &$value) $value = "'".$value."'";
                unset($value);
            }else{
                $groups="'{$groups}'";
            }
            $query->andWhere("S.name IN (" . implode(',', $groups) . ")");
        }

        $results = $query->setFirstResult(0)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
        return $results;
    }
}
