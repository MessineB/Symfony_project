<?php

namespace App\Security;

use App\Repository\UserRepository;
use League\OAuth2\Client\Token\AccessToken;
use Prophecy\Argument\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\GithubResourceOwner;
use Symfony\Component\HttpFoundation\RedirectResponse;
use KnpU\OAuth2ClientBundle\Client\Provider\GithubClient;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;

class GithubAuthenticator extends SocialAuthenticator
{
    private RouterInterface $router;
    private ClientRegistry $clientRegistry;

    public function __construct(RouterInterface $router, ClientRegistry $clientRegistry, UserRepository $userRepository)
    {
        $this->router = $router;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('app_login'));
    }

    public function supports(Request $request)
    {
        return 'oauth_check' === $request->attributes->get('_route') && $request->get('service') === 'github';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getClient());
    }

    /**
     * @param AccessToken $credentials
     */
    public function getUser($credentials)
    {
        /** @var GithubResourceOwner $githubUser */
        $githubUser = $this->getClient()->fetchUserFromToken($credentials);
        return $this->userRepository->findOrCreateFromGithubOauth($githubUser);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return new RedirectResponse('/');
    }

    private function getClient(): GithubClient
    {
        return $this->clientRegistry->getClient('github');
    }
}

