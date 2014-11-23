<?php

namespace PoleDev\AppBundle\Security;

use Guzzle\Service\Client;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;

class GithubAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    private $client;
    private $router;
    private $logger;

    public function __construct(Client $client, Router $router, LoggerInterface $logger, $clientId, $clientSecret)
    {
        $this->client = $client;
        $this->router = $router;
        $this->logger = $logger;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function createToken(Request $request, $providerKey)
    {
        $request = $this->client->post('/login/oauth/access_token', array(), array (
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $request->get('code'),
            'redirect_uri' => $this->router->generate('admin', array(), UrlGeneratorInterface::ABSOLUTE_URL)
        ));

        $response = $request->send();

        $data = $response->json();

        /* See all information coming from Github */
        /** var_dump($data) **/

        if (isset($data['error'])) {
            $message = sprintf('An error occured during authentication with Github. (%s)', $data['error_description']);
            $this->logger->notice(
                $message,
                array(
                    'HTTP_CODE_STATUS' => Response::HTTP_UNAUTHORIZED,
                    'error'  => $data['error'],
                    'error_description'  =>  $data['error_description'],
                )
            );

            throw new HttpException(Response::HTTP_UNAUTHORIZED, $message);
        }

        return new PreAuthenticatedToken('anon.', $data['access_token'], $providerKey);
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $user = $userProvider->loadUserByUsername($token->getCredentials());

        return new PreAuthenticatedToken($user, $token->getCredentials(), $providerKey, $user->getRoles());
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response("Authentication Failed.", Response::HTTP_UNAUTHORIZED);
    }
}
