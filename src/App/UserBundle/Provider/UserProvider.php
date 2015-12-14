<?php

namespace App\UserBundle\Provider;

use FOS\UserBundle\Model\UserInterface as FOSUserInterface;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider;

use App\UserBundle\Entity\User;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
* Loading and ad-hoc creation of a user by an OAuth sign-in provider account.
*
* @author Robin Chalas <robin.chalas@gmail.com>
*/
class UserProvider extends FOSUBUserProvider
{
		/**
		* {@inheritDoc}
		*/
		public function loadUserByOAuthUserResponse(UserResponseInterface $response)
		{
				try {
						return parent::loadUserByOAuthUserResponse($response);
				} catch (UsernameNotFoundException $e) {
						if (null === $user = $this->userManager->findUserByEmail($response->getEmail())) {
							return $this->createUserByOAuthUserResponse($response);
						}

						return $this->updateUserByOAuthUserResponse($user, $response);
				}
		}

		/**
		* {@inheritDoc}
		*/
		public function connect(UserInterface $user, UserResponseInterface $response)
		{
				$providerName = $response->getResourceOwner()->getName();
				$uniqueId = $response->getUsername();
				$user->addOAuthAccount($providerName, $uniqueId);

				$this->userManager->updateUser($user);
		}

		/**
		* Ad-hoc creation of user
		*
		* @param UserResponseInterface $response
		*
		* @return User
		*/
		protected function createUserByOAuthUserResponse(UserResponseInterface $response)
		{
				$user = $this->userManager->createUser();
				$this->updateUserByOAuthUserResponse($user, $response);
				// set default values taken from OAuth sign-in provider account
				if (null == $email = $response->getEmail()) {
					$email = 'oauth';
				}

				$user->setEmail($email);

				if (null === $this->userManager->findUserByUsername($response->getNickname())) {
					$user->setUsername($response->getNickname());
				}

				$user->setEnabled(true);

				return $user;
		}

		/**
		* Attach OAuth sign-in provider account to existing user
		*
		* @param FOSUserInterface      $user
		* @param UserResponseInterface $response
		*
		* @return FOSUserInterface
		*/
		protected function updateUserByOAuthUserResponse(FOSUserInterface $user, UserResponseInterface $response)
		{
				$providerName = $response->getResourceOwner()->getName();
				$providerNameSetter = 'set'.ucfirst($providerName).'Id';
				$user->$providerNameSetter($response->getUsername());

				if(!$user->getPassword()) {
						// generate unique token
						$user->setPlainPassword('oauth');
						$this->userManager->updatePassword($user);
				}

				return $user;
		}
}
