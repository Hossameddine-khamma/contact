<?php

namespace App\Security;

use App\Repository\UsersRepository;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\LinkedInClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\LinkedInResourceOwner;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LinkedInAutenticator extends SocialAuthenticator
{

    private RouterInterface $router;
    
    private ClientRegistry $clientRegistry;

    private UsersRepository $usersRepository;

    public function __construct(RouterInterface $router, ClientRegistry $clientRegistry, UsersRepository $usersRepository)
    {
        $this->router= $router;
        $this->clientRegistry= $clientRegistry;
        $this->usersRepository= $usersRepository;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('app_login'));
    }

    public function supports(Request $request)
    {
       return 'oauth_check' === $request->attributes->get('_route') && $request->get('service') === 'linkedin';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getCilent());
    }
    
    /***
     * @param AccessToken $credentials
     */
    public function getUser($credentials, UserProviderInterface $userProvider )
    {
        /**@var LinkedInResourceOwner  $LinkedinUser */
        $LinkedinUser= $this->getCilent()->fetchUserFromToken($credentials);
        return $this->usersRepository->findOrCreateFromOauth($LinkedinUser);

    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
       return new RedirectResponse('/users');
    }

    private function getCilent(): LinkedInClient{

        return $this->clientRegistry->getClient('linkedin');
    }
}
