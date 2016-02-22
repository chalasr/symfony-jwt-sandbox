<?php

namespace UserBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation as Http;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UserBundle\Entity\User;
use Util\Controller\AbstractRestController as BaseController;
use Util\Controller\CanCheckPermissionsTrait as CanCheckPermissions;

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
     * @Rest\View
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
        $repo = $em->getRepository('UserBundle:User');

        return $repo->findAll();
    }

    /**
     * Get a user by id.
     *
     * @Rest\Get("/users/{id}", requirements={"id" = "\d+"})
     * @Rest\View
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
        $repo = $em->getRepository('UserBundle:User');
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
        $repo = $em->getRepository('UserBundle:User');

        $picture = $request->files->get('file');

        $user->setFile($picture);

        $uploadPath = $this->locateResource('@UserBundle/Resources/public/pictures');

        if ($user->getFile()) {
            $user->uploadPicture($uploadPath);
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
    public function getPicture($id)
    {
        $user = $this->findUserOrFail($id);
        $picture = $this->locateResource('@UserBundle/Resources/public/pictures/'.$user->getPicture());

        if (!is_file($picture)) {
            $picture = $this->locateResource('@UserBundle/Resources/public/pictures/default.jpg');
        }

        $response = new Http\Response();
        $response->headers->set('Content-type', mime_content_type($picture));
        $response->headers->set('Content-length', filesize($picture));
        $response->sendHeaders();
        $response->setContent(file_get_contents($picture));

        return $response;
    }
}
