<?php

namespace App\UserBundle\Controller;

use App\Util\Validator\CanValidateTrait as CanValidate;
use App\Util\Controller\EntitySerializableTrait as EntitySerializable;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Mangages users from mobile app in API.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class SecurityController extends Controller
{
    use EntitySerializable, CanValidate;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->rules = array(
            'register' => ['password', 'email'],
            'basic'    => ['password', 'email'],
            'oauth'    => ['id', 'email', 'access_token'],
        );
    }

    /**
     * Register new user and process authentication.
     *
     * @Rest\View(serializerGroups={"api"})
     *
     * @ApiDoc(
     * 	 section="Security",
     *     parameters={
     *   	     {"name"="email", "dataType"="string", "required"=true, "description"="Email"},
     *         {"name"="password", "dataType"="string", "required"=true, "description"="Password"},
     *         {"name"="first_name", "dataType"="string", "required"=false, "description"="First name"},
     *         {"name"="last_name", "dataType"="string", "required"=false, "description"="Last name"},
     *     },
     * 	 statusCodes={
     * 	     201="Created (new user created, token generated, returns them with refresh_token)",
     * 	     422="Unprocessable Entity (missing/invalid parameters | email already exists)"
     * 	 },
     * )
     */
    public function registerUserAccountAction(Request $request)
    {
        $data = $request->request->all();
        $userManager = $this->getUserManager();

        if (false === $this->check($data, 'register')) {
            return $this->validationFailedException();
        }

        if ($userManager->findUserByEmail($data['email']) !== null) {
            $this->errors = array('email' => 'already exists');

            return $this->validationFailedException();
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
     * 	     401="Unauthorized (bad credentials)"
     * 	  },
     * )
     */
    public function authenticateUserAction()
    {
        /* Virtual method originally handled by Security Component */
    }

    /**
     * Authenticates user as guest to access READ resources.
     *
     * @ApiDoc(
     *    section="Security",
     * 	  statusCodes={
     * 	     200="OK (user authenticated as guest, returns token and refresh_token)",
     * 	     200="OK (user authenticated as guest, returns token and refresh_token)",
     * 	     404="Not found (guest user not found)"
     * 	  },
     * )
     *
     * @return Response Json Web token
     */
    public function authenticateGuestAction()
    {
        $userManager = $this->getUserManager();
        $guestId = 'guest@sportroops.fr';

        if (null === $guest = $userManager->findUserByEmail($guestId)) {
            throw new NotFoundHttpException('Impossible de trouver l\'utilisateur guest (email: \'guest@sportroops.fr\')');
        }

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
     * 	     422="Unprocessable Entity (missing parameters | invalid email|facebook_id)"
     * 	 },
     * )
     */
    public function authenticateByOAuthAction(Request $request)
    {
        $data = $request->request->all();

        if (false === $this->check($data, 'oauth')) {
            return $this->validationFailedException();
        }

        if (false === $this->isValidFacebookAccount($data['id'], $data['access_token'])) {
            throw new UnprocessableEntityHttpException('The given facebook_id has no valid account associated');
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
     *  },
     *  statusCodes={
     *  	 204="No Content (success)",
     *  	 404="Not Found (user does not exist)"
     *  }
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
            throw new NotFoundHttpException(
                sprintf('Aucun utilisateur trouvable avec email \'%s\'', $data['email'])
            );
        }

        $password = $this->getRandomPassword();
        $user->setPlainPassword($password);
        $userManager->updateUser($user);

        /* Retrieves User informations for e-mail content **/
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

        /* Prepares an email with data **/
        $message = \Swift_Message::newInstance()
            ->setSubject('Sportroops reset password')
            ->setFrom('support@sportroops.com')
            ->setTo($mailing['email'])
            ->setBody(
                $this->renderView('Emails/reset_password.html.twig', $mailing),
                'text/html'
            );

        /* Sends email **/
        $this->get('mailer')->send($message);

        /* Serializes data before return response **/
        $view = View::create()->setStatusCode(204);

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
        $user->setUsername($data['email']);
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
        $response = array(
            'token'         => $this->get('lexik_jwt_authentication.jwt_manager')->create($user),
            'refresh_token' => $this->attachRefreshToken($user),
            'user'          => json_decode($this->serialize($user, array('groups' => ['api']))),
        );

        if (null !== $user->getFacebookId()) {
            $response['user']['facebook_id'] = $user->getFacebookId();
        }

        return new JsonResponse($response, $statusCode);
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
    protected function isValidFacebookAccount($id, $accessToken)
    {
        $client = new \Goutte\Client();

        $endpoint = sprintf('https://graph.facebook.com/me?access_token=%s', $accessToken);
        $request = $client->request('GET', $endpoint);
        $response = json_decode($client->getResponse()->getContent());

        return $response->id == $id;
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
