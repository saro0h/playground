<?php

namespace PoleDev\AppBundle\Tests\Security;

use PoleDev\AppBundle\Security\GithubAuthenticator;

class GithubAuthenticatorTest extends \PHPUnit_Framework_TestCase
{
    private $prophet;

    public function testCreateToken()
    {
        $guzzleResponseObjectProphecy = $this->prophet->prophesize('Guzzle\Http\Message\Response');
        $guzzleResponseObjectProphecy
            ->json()
            ->willReturn(array('access_token' => 'a_fake_access_token'))
        ;
        $guzzleResponse = $guzzleResponseObjectProphecy->reveal();

        $guzzleRequestObjectProphecy = $this->prophet->prophesize('Guzzle\Http\Message\EntityEnclosingRequest');
        $guzzleRequestObjectProphecy->send()->willReturn($guzzleResponse);

        $guzzleRequest = $guzzleRequestObjectProphecy->reveal();

        $clientObjectProphecy = $this->prophet->prophesize('Guzzle\Service\Client');
        $clientObjectProphecy
            ->post('/login/oauth/access_token', array(), array (
                'client_id' => '',
                'client_secret' => '',
                'code' => '',
                'redirect_uri' => ''
            ))
            ->willReturn($guzzleRequest)
        ;
        $client = $clientObjectProphecy->reveal();

        $routerObjectProphecy = $this->prophet->prophesize('Symfony\Bundle\FrameworkBundle\Routing\Router');
        $router = $routerObjectProphecy->reveal();

        $loggerObjectProphecy = $this->prophet->prophesize('Psr\Log\LoggerInterface');
        $logger = $loggerObjectProphecy->reveal();

        $requestObjectProphecy = $this->prophet->prophesize('Symfony\Component\HttpFoundation\Request');
        $request = $requestObjectProphecy->reveal();

        $githubAuthenticator = new GithubAuthenticator($client, $router, $logger, '', '');

        $token = $githubAuthenticator->createToken($request, 'secure_area');

        $this->assertSame('a_fake_access_token', $token->getCredentials());
        $this->assertSame('secure_area', $token->getProviderKey());
        $this->assertSame('anon.', $token->getUser());
        $this->assertSame(array(), $token->getRoles());
        $this->assertSame(false, $token->isAuthenticated());
        $this->assertSame(array(), $token->getAttributes());
    }

    public function setUp()
    {
        $this->prophet = new \Prophecy\Prophet;
    }

    public function tearDown()
    {
        $this->prophet = null;
    }
}
