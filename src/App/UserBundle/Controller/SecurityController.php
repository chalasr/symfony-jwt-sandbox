<?php

namespace App\UserBundle\Controller;

use App\Util\Controller\CanSerializeTrait as CanSerialize;
use App\Util\Validator\CanValidateTrait as CanValidate;
use App\Util\Validator\Constraints\Email;
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
    use CanSerialize, CanValidate;

    /**
     * Register new user and process authentication.
     *
     * @Rest\Post("/register")
     * @Rest\View
     * @Rest\RequestParam(name="email", requirements=@Email, nullable=false, allowBlank=false)
     * @Rest\RequestParam(name="password", requirements="[^/]+", nullable=false, allowBlank=false)
     *
     * @ApiDoc(
     * 	 section="Security",
     *     parameters={
     *   	     {"name"="email", "dataType"="string", "required"=true, "description"="Email"},
     *         {"name"="password", "dataType"="string", "required"=true, "description"="Password"},
     *     },
     * 	 statusCodes={
     * 	     201="Created (new user created, token generated, returns them with refresh_token)",
     * 	     422="Unprocessable Entity (missing/invalid parameters | email already exists)"
     * 	 },
     * )
     */
    public function registerUserAccountAction(ParamFetcher $paramFetcher)
    {
        $data = $paramFetcher->all();
        $userManager = $this->getUserManager();

        if ($userManager->findUserByEmail($data['email']) !== null) {
            $this->errors = array('email' => 'already exists');

            return $this->validationFailedException();
        }

        return $this->generateToken($this->createUser($data), 201);
    }

    /**
     * Processes user authentication from email/password.
     *
     * @Rest\Post("/login")
     *
     * @Rest\RequestParam(name="email", requirements=@Email, nullable=false, allowBlank=false)
     * @Rest\RequestParam(name="password", requirements="[^/]+", nullable=false, allowBlank=false)
     *
     * @ApiDoc(
     *     section="Security",
     *     parameters={
     * 	     {"name"="email", "dataType"="string", "required"=true, "description"="Email"},
     *       {"name"="password", "dataType"="string", "required"=true, "description"="Password"},
     *     },
     * 	  statusCodes={
     * 	     200="OK (user authenticated, returns token, refresh_token and available user infos)",
     * 	     422="Unprocessable Entity (missing parameters)",
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
     * @Rest\Get("/guest/login")
     *
     * @ApiDoc(
     *    section="Security",
     * 	  statusCodes={
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
        $guestId = 'guest@rch.fr';

        if (null === $guest = $userManager->findUserByEmail($guestId)) {
            $guest = $this->createGuestUser();
        }

        return $this->generateToken($guest);
    }

    /**
     * Register/Authenticate user from OAuth Response.
     *
     * @Rest\Post("/oauth/login")
     *
     * @Rest\RequestParam(name="email", requirements=@Email, nullable=false, allowBlank=false)
     * @Rest\RequestParam(name="facebook_id", requirements="\d+", nullable=false, allowBlank=false)
     * @Rest\RequestParam(name="facebook_access_token", requirements="[^/]", nullable=false, allowBlank=false)
     *
     * @ApiDoc(
     * 	 section="Security",
     *     parameters={
     *         {"name"="email", "dataType"="string", "required"=true, "description"="Email credential"},
     *         {"name"="facebook_access_token", "dataType"="string", "required"=true, "description"="Facebook access_token"},
     *         {"name"="facebook_id", "dataType"="integer", "required"=true, "description"="Facebook ID"},
     *     },
     * 	 statusCodes={
     * 	     200="OK (token generated for existing user, returns it with available user infos ans refresh_token)",
     * 	     201="Created (new user created, access token generated, returns them with refresh_token)",
     * 	     422="Unprocessable Entity (missing parameters | invalid email|facebook_id)"
     * 	 },
     * )
     */
    public function authenticateByOAuthAction(ParamFetcher $paramFetcher)
    {
        $data = $paramFetcher->all();

        if (false === $this->isValidFacebookAccount($data['facebook_id'], $data['facebook_access_token'])) {
            throw new UnprocessableEntityHttpException('The given facebook_id has no valid account associated');
        }

        $userManager = $this->getUserManager();
        $existingByFacebookId = $userManager->findUserBy(['facebookId' => $data['facebook_id']]);
        $existingByEmail = $userManager->findUserBy(['email' => $data['email']]);

        if (null !== $existingByFacebookId) {
            return $this->generateToken($existingByFacebookId, 200);
        }

        if (null !== $existingByEmail) {
            $existingByEmail->setFacebookId($data['facebook_id']);
            $userManager->updateUser($existingByEmail);

            return $this->generateToken($existingByEmail, 200);
        }

        $data['password'] = $this->getRandomPassword();

        return $this->generateToken($this->createUser($data, true), 201);
    }

    /**
     * Reset expired Token.
     *
     * @Rest\Post("/refresh_token")
     *
     * @Rest\RequestParam(name="token", allowBlank=false, requirements="[^/]")
     * @Rest\RequestParam(name="refresh_token", allowBlank=false, requirements="[^/]")
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
     * @Rest\Post("/users/reset_password")
     * @Rest\RequestParam(name="email", requirements=@Email, allowBlank=false, description="User email")
     *
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

        $user = $userManager->createUser();
        $user->setUsername($data['email']);
        $user->setEmail($data['email']);
        $user->setEnabled(true);
        $user->setCreatedAt(new \DateTime());
        $user->setPlainPassword($data['password']);

        if (true === $isOAuth) {
            $user->setFacebookId($data['facebook_id']);
        }

        $userManager->updateUser($user);

        if (null === $user) {
            return;
        }

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
        $request = $client->request('GET', sprintf('https://graph.facebook.com/me?access_token=%s', $accessToken));
        $response = json_decode($client->getResponse()->getContent());

        if ($response->error) {
            throw new UnprocessableEntityHttpException($response->error->message);
        }

        return $response->id == $id;
    }

    /**
     * Creates the guest user.
     *
     * @return User The newly created guest user
     */
    protected function createGuestUser()
    {
        $em = $this->getEntityManager();
        $userManager = $this->getUserManager();
        $guestEmail = 'guest@rch.fr';

        $user = $userManager->createUser();
        $user->setUsername($guestEmail);
        $user->setEmail($guestEmail);
        $user->setEnabled(true);
        $user->addRole('ROLE_GUEST');
        $user->setCreatedAt(new \DateTime());
        $user->setPlainPassword('guest');

        $userManager->updateUser($user);

        return $user;
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
