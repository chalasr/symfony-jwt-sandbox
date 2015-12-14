<?php

namespace App\UserBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mangages users from mobile app in API.
 * @author Robin Chalas
 */
class SecurityController extends Controller
{
    protected $rules;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->rules = array(
            'oauth' => array(
                'id'    => 'required',
                'name'  => 'required',
            ),
            'basic' => array(
                'username' => 'required',
                'password' => 'required',
            ),
        );
    }

    /**
     * Register/login user from Facebook.
     *
     * @param Request $request Response from Facebook login
     *
     * @method POST
     *
     * @return JsonResponse $token
     */
    public function authenticateByOAuthAction(Request $request)
    {
        $data = $request->request->all();

        if (false == $this->validator($data, 'oauth')) {
            return new JsonResponse(['message' => 'Mandatory parameters are missing']);
        }

        $userManager = $this->get('fos_user.user_manager');
        $username = $password = strtolower(str_replace(' ', '', $data['name']));
        $existing = $userManager->findUserBy(['facebookId' => $data['id']]);

        if ($existing !== null) {
            return new JsonResponse(array(
                'token' => $this->get('lexik_jwt_authentication.jwt_manager')->create($existing),
            ));
        }
        $user = $this->createUser($data, $username, $password, true);

        return new JsonResponse(array(
            'token' => $this->get('lexik_jwt_authentication.jwt_manager')->create($user),
        ));
    }
    //
    // public function registerUserAccount(Request $request)
    // {
    //     if (false === $this->validator)
    // }

    /**
     * Get users list.
     *
     * @method GET
     *
     * @return JsonResponse $query results
     */
    public function getAllUsersAction()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppUserBundle:User');
        $query = $repo->createQuerybuilder('u')
        ->select('u.id', 'u.username', 'u.email')
        ->getQuery();

        return new JsonResponse($query->getResult());
    }

    /**
     * Render AngularJS demo application.
     *
     * @return resource Rendered view
     */
    public function demoAction()
    {
        return $this->render('AppUserBundle:Default:list.html.twig');
    }

    /**
     * Create user account.
     *
     * @param array  $data
     * @param string $username
     * @param string $password
     * @param bool   $isOAuth
     *
     * @return User $user
     */
    protected function createUser($data, $username, $password, $isOAuth = false)
    {
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $user->setUsername($username);
        $user->setPlainPassword($password);
        $user->setEnabled(true);

        if (isset($data['first_name'])) {
            $user->setFirstname($data['first_name']);
        }
        if (isset($data['last_name'])) {
            $user->setLastname($data['last_name']);
        }
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (true === $isOAuth) {
            $user->setFacebookId($data['id']);
        }

        $userManager->updateUser($user);

        return $user;
    }

    /**
     * Returns Entity Manager.
     *
     * @return EntityManager $entityManagers
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getEntityManager();
    }

    /**
     * Validates data based on $rules.
     *
     * @param array  $data
     * @param string $type Origin of signup
     *
     * @return bool $validator
     */
    protected function validator($data, $type = 'basic')
    {
        $validator = true;
        foreach ($this->rules[$type] as $k => $v) {
            if (false === isset($data[$k])) {
                $validator = false;
                break;
            }
        }

        return $validator;
    }
}
