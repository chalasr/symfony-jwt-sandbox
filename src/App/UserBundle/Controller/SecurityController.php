<?php

namespace App\UserBundle\Controller;

use Doctrine\ORM\EntityManager;
use Goutte\Client as HttpClient;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $this->rules = [
            'register' => ['password', 'email'],
            'basic'    => ['email', 'password'],
            'oauth'    => ['id', 'name', 'email', 'access_token'],
        ];
    }

    /**
     * Register new user and process authentication.
     *
     * @ApiDoc(
     * 	 section="Security",
     *   parameters={
     * 	   {"name"="email", "dataType"="string", "required"=true, "description"="Email"},
     *     {"name"="password", "dataType"="string", "required"=true, "description"="Password"},
     *     {"name"="first_name", "dataType"="string", "required"=false, "description"="First name"},
     *     {"name"="last_name", "dataType"="string", "required"=false, "description"="Last name"},
     *   },
     * 	 statusCodes={
     * 	   201="Created (new user created and token successfully generated)",
     * 	   422="Unprocessable Entity (missing parameters)"
     * 	 },
     * )
     */
    public function registerUserAccountAction(Request $request)
    {
        $data = $request->request->all();
        $userManager = $this->getUserManager();

        if (false == $this->validator($data, 'register')) {
            return $this->missingParametersError('register');
        }

        if ($userManager->findUserByEmail($data['email']) !== null) {
            return $this->resourceAlreadyExistsError('email', $data['email']);
        }

        if ($data['last_name'] !== null || $data['first_name'] !== null) {
            $data['name'] = sprintf('%s %s', $data['first_name'], $data['last_name']);
        } else {
            $data['name'] = $data['email'];
        }

        return $this->generateToken($this->createUser($data), 201);
    }

    /**
     * Register/Authenticate user from OAuth Response.
     *
     * @ApiDoc(
     * 	 section="Security",
     *   parameters={
     *     {"name"="id", "dataType"="integer", "required"=true, "description"="Facebook ID"},
     *     {"name"="access_token", "dataType"="string", "required"=true, "description"="Facebook access_token"},
     *     {"name"="name", "dataType"="string", "required"=true, "description"="Username"},
     *     {"name"="email", "dataType"="string", "required"=true, "description"="Email credential"},
     *     {"name"="first_name", "dataType"="string", "required"=false, "description"="Firstname"},
     *     {"name"="last_name", "dataType"="string", "required"=false, "description"="Lastname"},
     *   },
     * 	 statusCodes={
     * 	   200="OK (token successfully generated for existing user)",
     * 	   201="Created (new user created and access token successfully generated)",
     * 	   422="Unprocessable Entity (missing parameters)"
     * 	 },
     * )
     */
    public function authenticateByOAuthAction(Request $request)
    {
        $data = $request->request->all();

        if (false == $this->validator($data, 'oauth')) {
            return $this->missingParametersError('authenticate', 'oauth');
        }

        if (false === $this->isValidFacebookAccount($data['id'], $data['access_token'])) {
            return new JsonResponse(array(
                'message' => 'The given facebook_id has no valid account associated'
            ), 401);
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
     * Lists all users.
     *
     * @ApiDoc(
     * 	 section="User",
     * 	 resource=true,
     * 	 statusCodes={
     * 	   200="OK (list all users)",
     * 	   401="Unauthorized (this resource require an access token)"
     * 	 },
     * )
     */
    public function getAllUsersAction()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppUserBundle:User');
        $query = $repo->createQueryBuilder('u')
        ->select('u.id', 'u.username', 'u.email')
        ->getQuery();

        return new JsonResponse($query->getResult());
    }

    /**
    * Proceses user authentication from email/password.
    *
    * @ApiDoc(
    *   section="Security",
    *   parameters={
    * 	   {"name"="email", "dataType"="string", "required"=true, "description"="Email"},
    *     {"name"="password", "dataType"="string", "required"=true, "description"="Password"},
    *   },
    * 	statusCodes={
    * 	   200="OK (token successfully generated for newly created user)",
    * 	   422="Unprocessable Entity (missing parameters)"
    * 	},
    * )
    */
    public function authenticateUserAction()
    {
      // Handled by Security Component.
    }

    /**
     * Creates new User.
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

        foreach ($this->rules[$type] as $prop) {
            if (false === isset($data[$prop]) || null == $data[$prop]) {
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

    protected function isValidFacebookAccount($facebookId, $facebookAccessToken)
    {
        $client = new HttpClient();

        $endpoint = sprintf('https://graph.facebook.com/me?access_token=%s', $facebookAccessToken);
        $request  = $client->request('GET', $endpoint);
        $response = json_decode($client->getResponse()->getContent());

        return ($response->id == $facebookId);
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
        $required = implode('\', \'', array_values($this->rules[null == $origin ? $action : $origin]));

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
     * Returns authentication provider.
     *
     * @return UserManager $userManager
     */
    protected function getUserManager()
    {
        return $this->get('fos_user.user_manager');
    }
}
