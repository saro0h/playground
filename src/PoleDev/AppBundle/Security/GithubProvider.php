<?php

namespace PoleDev\AppBundle\Security;

use Guzzle\Service\Client;
use Psr\Log\LoggerInterface;
use PoleDev\AppBundle\Model\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GithubProvider implements UserProviderInterface
{
    private $client;
    private $logger;

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function loadUserByUsername($accessToken)
    {
        $guzzleRequest = $this->client->get('/user?access_token='.$accessToken);

        $response = $guzzleRequest->send();
        $data = $response->json();

        $user = new User();
        $user->createFrom($data);

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass($class)
    {
        return 'PoleDev\AppBundle\Model\User' === $class;
    }

}
