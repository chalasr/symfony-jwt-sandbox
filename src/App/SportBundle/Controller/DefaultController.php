<?php

namespace App\SportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @var array
     */
    protected $rules;

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

    public function authenticateByOAuthAction(Request $request)
    {
        $data = $request->request->all();
        if (false == $this->validator($data, 'oauth')) {
            return new JsonResponse(['message' => 'Mandatory parameters are missing']);
        }

        $username = $password = trim(strtolower($data['name']));
        $userManager = $this->get('fos_user.user_manager');
        $existing = $userManager->findUserBy(['facebookId' => $data['id']]);
        if ($existing == null) {
            $user = $this->registerUserFromOAuth($data, $username, $password);
        }
        $user = $userManager->findUserBy(['facebookId' => $data['id']]);
        $token = $this->get("lexik_jwt_authentication.jwt_manager")->create($user);

        return new JsonResponse(['token' => $token]);
    }

    protected function validator($data, $type = 'basic')
    {
        $validator = true;
        foreach($this->rules[$type] as $k => $v) {
            if (false === isset($data[$k])) {
                $validator = false;
                break;
            }
        }

        return $validator;
    }

    protected function registerUserFromOAuth($data, $username, $password)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        if (isset($data['first_name'])) {
            $user->setFirstname($data['first_name']);
        }
        if (isset($data['last_name'])) {
            $user->setLastname($data['last_name']);
        }
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        $user->setFacebookId($data['id']);
        $user->setUsername($username);
        $user->setPlainPassword($password);
        $user->setEnabled(true);
        
        $userManager->updateUser($user);

        return $user;
    }

    public function indexAction()
    {
        return $this->render('AppUserBundle:Default:list.html.twig');
    }

    public function securedAction()
    {
        // return $this->redirect($this->generateUrl('sonata_admin_dashboard'));
        return new Response('Secured!');
    }

    public function getUsersListAction()
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository('AppUserBundle:User');
        $query = $repo->createQuerybuilder('u')
        ->select('u.username', 'u.email')
        ->getQuery();

        return new JsonResponse($query->getResult());
    }

    protected function getEntityManager()
    {
        return $this->getDoctrine()->getEntityManager();
    }
}
