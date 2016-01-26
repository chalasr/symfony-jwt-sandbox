<?php

namespace App\UserBundle\Controller;

use App\UserBundle\Entity\User;
use App\Util\Controller\AbstractRestController as BaseController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends BaseController
{
    /**
     * Lists all users.
     *
     * @Rest\Get("/users")
     * @ApiDoc(
     * 	 section="User",
     * 	 resource=true,
     * 	 statusCodes={
     * 	     200="OK (list all users)",
     * 	     401="Unauthorized (this resource require an access token)"
     * 	 },
     * )
     *
     * @return Doctrine\ORM\QueryBuilder $results
     */
    public function getAllUsersAction()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppUserBundle:User');
        $query = $repo->createQueryBuilder('u')
            ->select('u.id', 'u.email', 'u.firstname', 'u.lastname')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Get User by identfier.
     *
     * @Rest\Get("/users/{id}", requirements={"id" = "\d+"})
     * @Rest\View(serializerGroups={"api"})
     * @ApiDoc(
     * 	 section="User",
     * 	 resource=true,
     * 	 statusCodes={
     * 	     200="OK",
     * 	     401="Unauthorized (this resource require an access token)"
     * 	     422="Unprocessable Entity (self-following in forbidden|The user is already in followers)"
     * 	 },
     * )
     *
     * @return array
     */
    public function getUserAction($id)
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppUserBundle:User');
        $user = $repo->find($id);

        return $user;
    }

    /**
     * Add a follower from current user to another.
     *
     * @Rest\Post("/users/followers/{follower}", requirements={"follower" = "\d+"})
     * @ApiDoc(
     * 	section="User",
     * 	resource=true,
     * 	parameters={
     *     {"name"="follower", "dataType"="integer", "required"=true, "description"="Follower"}
     *   },
     * 	 statusCodes={
     * 	   204="No Content (follower successfully added)",
     * 	   401="Unauthorized (this resource require an access token)",
     * 	   422="Unprocessable Entity (self-following in forbidden|The user is already in followers)"
     * 	 },
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
     * Remove a followed user from the current user.
     *
     * @Rest\Delete("/users/followers/{follower}", requirements={"follower" = "\d+"})
     * @ApiDoc(
     * 	section="User",
     * 	resource=true,
     * 	parameters={
     *     {"name"="follower", "dataType"="integer", "required"=true, "description"="Follower"}
     *   },
     * 	 statusCodes={
     * 	   204="No Content (follower successfully deleted)",
     * 	   401="Unauthorized (this resource require an access token)"
     * 	 },
     * )
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
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
     * Add a followed user to the current user.
     *
     * @Rest\Post("/users/follows/{followed}", requirements={"followed" = "\d+"})
     * @ApiDoc(
     * 	section="User",
     * 	resource=true,
     * 	parameters={
     *     {"name"="followed", "dataType"="integer", "required"=true, "description"="Followed"}
     *   },
     * 	 statusCodes={
     * 	   204="No Content (follow successfully added)",
     * 	   401="Unauthorized (this resource require an access token)"
     * 	 },
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
     * 	section="User",
     * 	resource=true,
     * 	parameters={
     *     {"name"="follow", "dataType"="integer", "required"=true, "description"="Follow"}
     *   },
     * 	 statusCodes={
     * 	   204="No Content (follow successfully deleted)",
     * 	   401="Unauthorized (this resource require an access token)",
     * 	   422="Unprocessable Entity (User not followed yet)"
     * 	 },
     * )
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
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
     * Lists all followers.
     *
     * @Rest\Get("/users/{id}/followers")
     * @ApiDoc(
     * 	 section="User",
     * 	 resource=true,
     * 	 statusCodes={
     * 	     200="OK (list all followers)",
     * 	     401="Unauthorized (this resource require an access token)",
     * 	     404="User not found"
     * 	 },
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
     * Lists all followers.
     *
     * @Rest\Get("/users/{id}/follows")
     * @ApiDoc(
     * 	 section="User",
     * 	 resource=true,
     * 	 statusCodes={
     * 	     200="OK (list all followers)",
     * 	     401="Unauthorized (this resource require an access token)",
     * 	     404="User not found"
     * 	 },
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
     * Get user.
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
     * Check if user is the current user.
     *
     * @param  User $user
     *
     * @return boolean
     */
    protected function isCurrentUser($user)
    {
        $currentUser = $this->getCurrentUser();

        return $user->getId() == $currentUser->getId();
    }


    /**
     * update user picture.
     *
     * @Rest\Post("/users/{id}/picture", requirements={"id" = "\d+"})
     * @Rest\RequestParam(name="file",description="Picture")
     * @ApiDoc(
     * 	section="User",
     * 	resource=true,
     *
     * 	 statusCodes={
     * 	   204="No Content (picture successfully updated)",
     * 	   401="Unauthorized (this resource require an access token)"
     * 	 },
     * )
     *
     * @param Request $request
     *
     * @return array
     */
    public function updatePicture($id,Request $request)
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppUserBundle:User');

        $picture = $request->files->get('file');

        $user = $this->findUserOrFail($id);
        $user->setFile($picture);

        $uploadPath = $this->locateResource('@AppUserBundle/Resources/public/pictures');

        if ($user->getFile()) {
            $user->uploadPicture($uploadPath);
            $em->persist($user);
            $em->flush();
        }

        return $user;
    }

}
