<?php

namespace App\UserBundle\Controller;

use App\SportBundle\Entity;
use App\UserBundle\AppUserBundle;
use App\UserBundle\Entity\User;
use App\Util\Controller\AbstractRestController as BaseController;
use App\Util\Controller\CanCheckPermissionsTrait as CanCheckPermissions;
use App\Util\Validator\CanValidateTrait as CanValidate;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation as Http;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Users Controller.
 *
 * @author Pham Xuan Thuy <phamxuanthuy@sutunam.com>
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class UsersController extends BaseController
{
    use CanCheckPermissions, CanValidate;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->rules = array(
            'edit' => [
                'password'      => 'nonempty',
                'email'         => 'nonempty|email',
                'firs_tname'    => 'nonempty',
                'last_name'     => 'nonempty',
                'date_of_birth' => 'nonempty',
                'description'   => 'nonempty',
                'address'       => 'nonempty',
                'city'          => 'nonempty',
                'zipcode'       => 'nonempty',
            ],
        );
    }

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

         if ($user->getEmail() == 'guest@sportroops.fr') {
             return new JsonResponse([], 204);
         }

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
     *       401="Unauthorized (this resource require an access token)",
     *       403="Forbidden (must be the user or an admin)"
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
     *       204="No Content (picture successfully get)",
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

        $path_picture = $this->locateResource('@AppUserBundle/Resources/public/pictures/'.$user->getPicture());

        if(!$path_picture OR !file_exists($path_picture)){
            $path_picture = $this->locateResource('@AppUserBundle/Resources/public/pictures/').'default.jpg';
        }
        $iconInfo = pathinfo($path_picture);

        if (false === isset($iconInfo['extension'])) {
            throw new AccessDeniedHttpException('This resource is invalid extension');
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
     *
     * @ApiDoc(
     *     section="User",
     *     resource=true,
     *     statusCodes={
     *         204="No content (success)",
     *         401="Unauthorized (this resource require an access token)",
     *         404="User not found",
     *         403="Forbidden (must be the user or an admin)"
     *     },
     * )
     *
     * @param int          $id
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function addSport($id, ParamFetcher $paramFetcher)
    {
        $sportId = $paramFetcher->get('sport_id');
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
     * @param int          $id
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
     * Search users by name, groups, sports.
     *
     * @Rest\Post("/users/search")
     * @ApiDoc(
     *     section="User",
     *     resource=true,
     *     statusCodes={
     *        200="OK (list users)",
     * 	      401="Unauthorized (this resource require an access token)",
     * 	      404="User not found",
     *     },
     *      filters={
     *          {"name"="name", "dataType"="string"},
     *          {"name"="sports", "dataType"="string", "Example"="sports = sport1,sport2"},
     *          {"name"="groups", "dataType"="string", "Example"="groups = group1,group2"},
     *      }
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
            ->from('AppUserBundle:User', 'U');

        if ($groups) {
            $groups = array_filter(explode(',', $groups), 'trim');
            $query->JOIN('U.group', 'G', 'WITH', 'G.name IN (:groups)')
            ->setParameter('groups', $groups);
        }
        if ($sports) {
            $sports = array_filter(explode(',', $sports), 'trim');
            $query->JOIN('U.sportUsers', 'SU')
                ->JOIN('SU.sport', 'S', 'WITH', 'S.name IN (:sports)')
                ->setParameter('sports', $sports);
        }
        if ($name) {
            $query->Where('U.firstname LIKE :firstname')
                ->orWhere('U.lastname LIKE :lastname')
                ->setParameter('firstname', '%'.$name.'%')
                ->setParameter('lastname', '%'.$name.'%');
        }

        $results = $query->setFirstResult(0)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
        if (!$results) {
            throw new NotFoundHttpException(sprintf('Unable to find user '));
        }

        return $results;
    }

    /**
     * Update current user profile.
     *
     * @Rest\Patch("/users/profile")
     * @ApiDoc(
     *   section="User",
     *     resource=true,
     *     statusCodes={
     *         200="OK",
     *         401="Unauthorized (this resource require an access token)",
     *         400="invalid data format",
     *         404="Invalid sport port id (sport_id don't exist in system)"
     *     },
     *     parameters={
     *      {"name"="first_name", "dataType"="string", "required"=false, "description"="first_name"},
     *      {"name"="last_name", "dataType"="string", "required"=false, "description"="last_name"},
     *      {"name"="email", "dataType"="string", "required"=false, "description"="email"},
     *      {"name"="old_password", "dataType"="string", "required"=false, "description"="old password"},
     *      {"name"="password", "dataType"="string", "required"=false, "description"="password"},
     *      {"name"="date_of_birth", "dataType"="integer", "required"=false, "description"="date_of_birth"},
     *      {"name"="address", "dataType"="string", "required"=false, "description"="address"},
     *      {"name"="city", "dataType"="string", "required"=false, "description"="city"},
     *      {"name"="zipcode", "dataType"="string", "required"=false, "description"="zipcode"},
     *      {"name"="sports", "dataType"="string", "required"=false, "description"="sports list ids, List all sports of user (if don't exist, then add new sport else remove sport), Example: sports= 1,2,3,5"}
     *
     *     },
     *
     * )
     *
     * @param Request $request
     *
     * @return array
     */
    public function updateCurrentUserProfile(Request $request)
    {
        $em = $this->getEntityManager();

        $data = $request->request->all();
        $user = $this->getCurrentUser();
        $this->check($data, 'edit', true);
        if (count($this->errors)) {
            return $this->errors;
        }


        $uploadPath = $this->locateResource('@AppUserBundle/Resources/public/pictures');


        #check password
        if (isset($data['password'])) {

            if(!isset($data['old_password'])){
                return array('password' => 'old password invalid');
            }

            $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
            $old_pwd_encoded = $encoder->encodePassword($data['old_password'], $user->getSalt());

            if($user->getPassword() != $old_pwd_encoded){
                return array('password' => 'old password invalid');
            }

            if($data['old_password']==$data['password']){
                return array('password' => 'Old password same as current password');
            }

            $user->setPlainPassword($data['password']);
        }

        if (isset($data['email'])) {
            $userEmail = $em->getRepository('AppUserBundle:User')->findOneBy(array('email' => $data['email']));
            if ($userEmail) {
                if ($userEmail->getId() != $user->getId()) {
                    return array('email' => 'Exist email on system');
                }
            } else {
                $user->setEmail($data['email']);
            }
        }
        if (isset($data['first_name'])) {
            $user->setFirstName($data['first_name']);
        }
        if (isset($data['last_name'])) {
            $user->setLastName($data['last_name']);
        }
        if (isset($data['date_of_birth'])) {
            $user->setDateOfBirth($data['date_of_birth']);
        }
        if (isset($data['description'])) {
            $user->setDescription($data['description']);
        }
        if (isset($data['address'])) {
            $user->setAddress($data['address']);
        }
        if (isset($data['city'])) {
            $user->setCity($data['city']);
        }
        if (isset($data['zipcode'])) {
            $user->setZipcode($data['zipcode']);
        }


        if(isset($data['sports'])){
            $newSports = array_filter(explode(',', $data['sports']), 'intval');
            $currentSports=$user->getFullSports();

            $currentSportsId=array();

            #remove sport
            foreach($currentSports as $sport){
                if(!in_array($sport['id'],$newSports)){
                    $sportUsers = $em->getRepository('AppSportBundle:SportUser')->findOneBy(array('user' => $user->getId(), 'sport' => $sport['id']));
                    $em->remove($sportUsers);
                }
                array_push($currentSportsId,$sport['id']);
            }

            #add sport
            $repo = $em->getRepository('AppSportBundle:Sport');
            foreach($newSports as $sportId){
                if(!in_array($sportId,$currentSportsId)){
                    $sportUsers = $em->getRepository('AppSportBundle:SportUser')->findOneBy(array('user' => $user->getId(), 'sport' => $sportId));
                    if(!$sportUsers){
                        $sport = $repo->findOrFail($sportId);
                        $sportUser = new Entity\SportUser();
                        $sportUser->setUser($user);
                        $sportUser->setSport($sport);
                        $em->persist($sportUser);
                    }
                }
            }
        }


        $em->flush();
        return $data;
    }
}
