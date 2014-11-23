<?php

namespace PoleDev\AppBundle\Tests\Security;

use PoleDev\AppBundle\Security\GithubProvider;
use PoleDev\AppBundle\Model\User;

class GithubProviderTest extends \PHPUnit_Framework_TestCase
{
    private $prophet;

    public function testLoadByUsername()
    {
        $data = array(
            'id' => 1,
            'login' => 'login',
            'name'  => 'name',
            'email'  => 'email@email.com',
            'avatar_url' => 'http://url.com',
        );

        $userProviderObjectProphecy = $this->prophet->prophesize('PoleDev\AppBundle\Security\GithubProvider');
        $userProvider = $userProviderObjectProphecy->reveal();

        $guzzleResponseObjectProphecy = $this->prophet->prophesize('Guzzle\Http\Message\Response');
        /* Mock with prediction */
        $guzzleResponseObjectProphecy
            ->json()
            ->willReturn($data)
            ->shouldBeCalledTimes(1)
        ;
        $guzzleResponse = $guzzleResponseObjectProphecy->reveal();

        $guzzleRequestObjectProphecy = $this->prophet->prophesize('Guzzle\Http\Message\EntityEnclosingRequest');
        $guzzleRequestObjectProphecy
            ->send()
            ->willReturn($guzzleResponse)
            ->shouldBeCalled()
        ;

        $guzzleRequest = $guzzleRequestObjectProphecy->reveal();

        $clientObjectProphecy = $this->prophet->prophesize('Guzzle\Service\Client');
        $clientObjectProphecy
            ->get('/user?access_token=a_fake_access_token')
            ->willReturn($guzzleRequest)
        ;
        $client = $clientObjectProphecy->reveal();

        $loggerObjectProphecy = $this->prophet->prophesize('Psr\Log\LoggerInterface');
        $logger = $loggerObjectProphecy->reveal();

        $githubProvider = new GithubProvider($client, $logger);
        $user = $githubProvider->loadUserByUsername('a_fake_access_token');

        /* Spy */
        $clientObjectProphecy->get('/user?access_token=a_fake_access_token')->shouldHaveBeenCalled();

        $expectedUser = new User();
        $expectedUser->createFrom($data);

        $this->assertEquals($expectedUser, $user);
    }

    public function setUp()
    {
        $this->prophet = new \Prophecy\Prophet;
    }

    public function tearDown()
    {
        $this->prophet->checkPredictions();
        $this->prophet = null;
    }
}
