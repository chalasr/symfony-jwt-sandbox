<?php

namespace App\UserBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Goutte\Client as HttpClient;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use App\Util\Validator\Constraints\Email;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;

/**
 * Mangages users from mobile app in API.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
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
            'basic'    => ['password', 'email'],
            'oauth'    => ['id', 'email', 'access_token'],
        ];
    }

    /**
     * Register new user and process authentication.
     *
     * @ApiDoc(
     * 	 section="Security",
     *     parameters={
     * 	     {"name"="email", "dataType"="string", "required"=true, "description"="Email"},
     *         {"name"="password", "dataType"="string", "required"=true, "description"="Password"},
     *         {"name"="first_name", "dataType"="string", "required"=false, "description"="First name"},
     *         {"name"="last_name", "dataType"="string", "required"=false, "description"="Last name"},
     *     },
     * 	 statusCodes={
     * 	     201="Created (new user created, token generated, returns them with refresh_token)",
     * 	     422="Unprocessable Entity (missing parameters)"
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

        $firstname = isset($data['first_name']) ? $data['fist_name'] : null;
        $lastname = isset($data['last_name']) ? $data['last_name'] : null;

        if (null !== $firstname && null !== $lastname) {
            $data['name'] = sprintf('%s %s', $firstname, $lastname);
        } else {
            $data['name'] = $data['email'];
        }
        if ($userManager->findUserByUsername($data['name']) !== null) {
            return $this->resourceAlreadyExistsError('name', $data['name']);
        }

        return $this->generateToken($this->createUser($data), 201);
    }

    /**
     * Processes user authentication from email/password.
     *
     * @ApiDoc(
     *     section="Security",
     *     parameters={
     * 	     {"name"="email", "dataType"="string", "required"=true, "description"="Email"},
     *        {"name"="password", "dataType"="string", "required"=true, "description"="Password"},
     *     },
     * 	  statusCodes={
     * 	     200="OK (user authenticated, returns token, refresh_token and available user infos)",
     * 	     422="Unprocessable Entity (missing parameters)"
     * 	  },
     * )
     */
    public function authenticateUserAction()
    {
        /** Virtual method originally handled by Security Component */
    }

    /**
     * Authenticates user as guest to access READ resources.
     *
     * @ApiDoc(
     *    section="Security",
     * 	  statusCodes={
     * 	     200="OK (user authenticated as guest, returns token and refresh_token)",
     * 	  },
     * )
     *
     * @return Response Json Web token
     */
    public function authenticateGuestAction()
    {
        $userManager = $this->getUserManager();

        $guest = $userManager->findUserByUsername('guest');

        return $this->generateToken($guest);
    }

    /**
     * Register/Authenticate user from OAuth Response.
     *
     * @ApiDoc(
     * 	 section="Security",
     *     parameters={
     *         {"name"="id", "dataType"="integer", "required"=true, "description"="Facebook ID"},
     *         {"name"="access_token", "dataType"="string", "required"=true, "description"="Facebook access_token"},
     *         {"name"="email", "dataType"="string", "required"=true, "description"="Email credential"},
     *         {"name"="first_name", "dataType"="string", "required"=false, "description"="Firstname"},
     *         {"name"="last_name", "dataType"="string", "required"=false, "description"="Lastname"},
     *     },
     * 	 statusCodes={
     * 	     200="OK (token generated for existing user, returns it with available user infos ans refresh_token)",
     * 	     201="Created (new user created, access token generated, returns them with refresh_token)",
     * 	     422="Unprocessable Entity (missing parameters)"
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
                'message' => 'The given facebook_id has no valid account associated',
            ), 401);
        }

        $userManager = $this->getUserManager();
        $existingByFacebookId = $userManager->findUserBy(['facebookId' => $data['id']]);
        $existingByEmail = $userManager->findUserBy(['email' => $data['email']]);

        if (null !== $existingByFacebookId) {
            return $this->generateToken($existingByFacebookId, 200);
        }

        if (null !== $existingByEmail) {
            $existingByEmail->setFacebookId($data['id']);
            $userManager->updateUser($existingByEmail);

            return $this->generateToken($existingByEmail, 200);
        }

        $data['password'] = $this->getRandomPassword();

        return $this->generateToken($this->createUser($data, true), 201);
    }

    /**
     * Reset expired Token.
     *
     * @ApiDoc(
     * 	section="Security",
     * 	parameters={
     *     {"name"="token", "dataType"="string", "required"=true, "description"="Expired token"},
     *     {"name"="refresh_token", "dataType"="string", "required"=true, "description"="Refresh token"},
     *   }
     * )
     */
    public function refreshTokenAction(Request $request)
    {
        return $this->forward('gesdinet.jwtrefreshtoken:refresh', array(
            'request' => $request,
        ));
    }

    /**
     * Resets lost password for a given user.
     *
     * @Rest\RequestParam(name="email", requirements=@Email, allowBlank=false, description="User email")
     * @ApiDoc(
     * 	section="Security",
     * 	parameters={
     *     {"name"="email", "dataType"="string", "required"=true, "description"="User email"}
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return View
     */
    public function resetPasswordAction(Request $request)
    {
        $userManager = $this->getUserManager();
        $data = $request->request->all();

        if (null === $user = $userManager->findUserBy(['email' => $data['email']])) {
            throw new UnprocessableEntityHttpException(
                sprintf('Aucun utilisateur trouvable avec email \'%s\'', $data['email'])
            );
        }

        $password = $this->getRandomPassword();
        $user->setPlainPassword($password);
        $userManager->updateUser($user);

        /* Get User informations for e-mail content **/
        $query = $this->getEntityManager()
            ->createQueryBuilder('u')
            ->select('u.firstname', 'u.lastname', 'u.email')
            ->from('App\UserBundle\Entity\User', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $data['email'])
            ->getQuery();

        $result = $query->getResult();

        $mailing = array(
            'lastname'  => $result[0]['lastname'],
            'firstname' => $result[0]['firstname'],
            'email'     => $result[0]['email'],
            'password'  => $password,
        );

        /* Prepare an email with data **/
        $message = \Swift_Message::newInstance()
            ->setSubject('Sportroops reset password')
            ->setFrom('support@sportroops.com')
            ->setTo($mailing['email'])
            ->setBody(
                $this->renderView('Emails/reset_password.html.twig', $mailing),
                'text/html'
            );

        /* Send email **/
        $this->get('mailer')->send($message);

        /* Serialize data before return response **/
        $view = View::create()
           ->setStatusCode(200)
           ->setData($user);

        return $this->get('fos_rest.view_handler')->handle($view);
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

        $em = $this->getEntityManager();
        $group = $em->getRepository('AppUserBundle:Group')->findOneByName(['name' => 'Sportroopers']);
        $user = $userManager->createUser();
        $user->setUsername($data['name']);
        $user->setEmail($data['email']);
        $user->setGroup($group);
        $user->setEnabled(true);
        $user->setCreatedAt(new \DateTime());
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
     * Generates token from user.
     *
     * @param User $user
     *
     * @return JsonResponse $token
     */
    protected function generateToken($user, $statusCode = 200)
    {
        $serializer = SerializerBuilder::create()->build();
        $context = SerializationContext::create()
            ->setGroups(array('api'))
            ->setSerializeNull(true);

        $response = array(
            'token'         => $this->get('lexik_jwt_authentication.jwt_manager')->create($user),
            'refresh_token' => $this->attachRefreshToken($user),
        );

        $response['user'] = json_decode($serializer->serialize($user, 'json', $context));

        if (null !== $user->getFacebookId()) {
            $response['user']['facebook_id'] = $user->getFacebookId();
        }

        return new JsonResponse($response, $statusCode);
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
     * Provides a refresh token.
     *
     * @param UserManager $user
     *
     * @return string refresh_token
     */
    protected function attachRefreshToken($user)
    {
        $refreshTokenManager = $this->get('gesdinet.jwtrefreshtoken.refresh_token_manager');
        $refreshToken = $refreshTokenManager->getLastFromUsername($user->getUsername());

        if (!$refreshToken instanceof RefreshToken) {
            $datetime = new \DateTime();
            $datetime->modify('+2592000 seconds');

            $refreshToken = $refreshTokenManager->create();
            $refreshToken->setUsername($user->getUsername());
            $refreshToken->setRefreshToken();
            $refreshToken->setValid($datetime);

            $refreshTokenManager->save($refreshToken);
        }

        return $refreshToken->getRefreshToken();
    }

    /**
     * Verifiy facebook account from id/access_token.
     *
     * @param int    $facebookId          Facebook account id
     * @param string $facebookAccessToken Facebook access_token
     *
     * @return bool Facebook account status
     */
    protected function isValidFacebookAccount($facebookId, $facebookAccessToken)
    {
        $client = new HttpClient();

        $endpoint = sprintf('https://graph.facebook.com/me?access_token=%s', $facebookAccessToken);
        $request = $client->request('GET', $endpoint);
        $response = json_decode($client->getResponse()->getContent());

        return $response->id == $facebookId;
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
            'message' => sprintf('Un utilisateur existe déjà avec %s \'%s\'', $prop, $val),
        ), 422);
    }

    /**
     * Returns Entity Manager.
     *
     * @return EntityManager $entityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
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

    /**
     * Generates a random password of 8 characters.
     *
     * @return string
     */
    protected function getRandomPassword()
    {
        $tokenGenerator = $this->get('fos_user.util.token_generator');

        return substr($tokenGenerator->generateToken(), 0, 8);
    }
}
