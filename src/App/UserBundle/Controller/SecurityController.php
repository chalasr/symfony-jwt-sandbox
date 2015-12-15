<?php

namespace App\UserBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Mangages users from mobile app in API.
 *
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
                'email' => 'required',
            ),
            'basic' => array(
                'email' => 'required',
                'password' => 'required',
            ),
            'register' => array(
                'password' => 'required',
                'email'    => 'required',
            ),
        );
    }

    /**
     * Register/login user from Facebook.
     *
     * @ApiDoc(
     *   parameters={
     *     {"name"="id",   "dataType"="integer", "required"=true, "description"="Facebook user ID"},
     *     {"name"="name", "dataType"="string", "required"=true, "description"="Full name"},
     *     {"name"="email", "dataType"="string", "required"=true, "description"="Email used for signin"},
     *   }
     * )
     * @param Request $request Response from Facebook login
     *
     * @return JsonResponse $token
     */
    public function authenticateByOAuthAction(Request $request)
    {
        $data = $request->request->all();

        if (false == $this->validator($data, 'oauth')) {
            return $this->missingParametersError('authenticate', 'oauth');
        }

        $userManager = $this->getUserManager();
        $existingByFacebookId = $userManager->findUserBy(['facebookId' => $data['id']]);
        $existingByEmail = $userManager->findUserBy(['email' => $data['email']]);

        if ($existingByFacebookId !== null) {
            return $this->generateToken($existingByFacebookId, 200);
        }

        if ($existingByEmail !== null) {
            $existingByEmail->setFacebookId($data['id']);
            $userManager->updateUser($existingByEmail);

            return $this->generateToken($existingByEmail, 200);
        }

        $data['password'] = strtolower(str_replace(' ', '', $data['name']));

        return $this->generateToken($this->createUser($data, true), 201);
    }

    /**
     * Create user account from Request.
     *
     * @ApiDoc(
     *   parameters={
     * 	   {"name"="email", "dataType"="string", "required"=true, "description"="Email"},
     *     {"name"="password", "dataType"="string", "required"=true, "description"="Password"},
     *   }
     * )
     *
     * @param Request $request
     *
     * @return User $user Newly created
     */
    public function registerUserFromRequestAction(Request $request)
    {
        $data = $request->request->all();
        $userManager = $this->getUserManager();

        if (false == $this->validator($data, 'register')) {
            return $this->missingParametersError('register');
        }

        if ($userManager->findUserByEmail($data['email']) !== null) {
            return $this->resourceAlreadyExistsError('email', $data['email']);
        }

        if (isset($data['last_name']) && isset($data['first_name'])) {
            $data['name'] = sprintf('%s %s', $data['first_name'], $data['last_name']);
        } else {
            $data['name'] = $data['email'];
        }

        return $this->generateToken($this->createUser($data), 201);
    }

    /**
     * Get users list.
     *
     * @ApiDoc()
     *
     * @return JsonResponse $query results
     *
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
     * Create new User.
     *
     * @param array  $data
     * @param string $username
     * @param string $password
     * @param bool   $isOAuth
     *
     * @return User $user
     */
    protected function createUser($data, $isOAuth = false)
    {
        $userManager = $this->getUserManager();

        $user = $userManager->createUser();
        $user->setUsername($data['name']);
        $user->setEmail($data['email']);
        $user->setEnabled(true);
        $user->setPlainPassword($data['password']);

        if (true === $isOAuth) {
            $user->setFacebookId($data['id']);
        }
        if (isset($data['first_name'])) {
            $user->setFirstname($data['first_name']);
        }
        if (isset($data['last_name'])) {
            $user->setLastname($data['last_name']);
        }

        $userManager->updateUser($user);

        return $user;
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
            if (false === isset($data[$k]) || null == $data[$k]) {
                $validator = false;
                break;
            }
        }

        return $validator;
    }

    /**
     * Generates token from user.
     *
     * @param User $user
     *
     * @return JsonResponse $token
     */
    protected function generateToken($user, $statusCode = 200)
    {
        return new JsonResponse(array(
            'token' => $this->get('lexik_jwt_authentication.jwt_manager')->create($user),
        ));
    }

    /**
     * Returns an error caused by valid format but not good data.
     *
     * @param string $action
     * @param string $user
     *
     * @return JsonResponse Unprocessable entity 422
     */
    protected function missingParametersError($action, $origin = null)
    {
        $required = implode('\', \'', array_keys($this->rules[null == $origin ? $action : $origin]));

        return new JsonResponse(array(
            'message' => sprintf('Some mandatory parameters are missing for %s user (required: \'%s\')', $action, $required),
        ), 422);
    }

    /**
     * Returns an error caused by already existing entity on try to create a new.
     *
     * @param string $prop The property used
     * @param string $val  Value of property
     *
     * @return JsonResponse Unprocessable entity 422
     */
    protected function resourceAlreadyExistsError($prop, $val)
    {
        return new JsonResponse(array(
            'message' => sprintf('User with %s \'%s\' already exists', $prop, $val),
        ), 422);
    }

    /**
     * Returns Entity Manager.
     *
     * @return EntityManager $entityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getEntityManager();
    }

    /**
     * Returns User Manager.
     *
     * @return UserManager $userManager
     */
    protected function getUserManager()
    {
        return $this->get('fos_user.user_manager');
    }
}
