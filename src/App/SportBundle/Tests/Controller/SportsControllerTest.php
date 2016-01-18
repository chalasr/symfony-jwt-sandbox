<?php

namespace App\SportBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use GuzzleHttp\Client;

/**
 * Tests the SportsController.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
class SportsControllerTest extends \PHPUnit_Framework_TestCase
{
    /* @var Client */
    protected $client;

    /* @var string JsonWebToken */
    protected $token;

    public function setUp()
    {
        parent::setUp();

        $this->client = new Client([
            'base_url' => 'http://api.sportroops.dev/v1/',
        ]);

        $this->token = $this->createUser([
            'email'    => 'admin',
            'password' => 'admin',
        ]);
    }

    /**
     * Creates an authenticated user.
     *
     * @param  array  $credentials
     *
     * @method POST
     *
     * @return string The Json Web Token
     */
    protected function createUser(array $credentials)
    {
        $request = $this->client->createRequest('POST', 'login_check', ['body' => $credentials]);
        $response = $this->client->send($request);

        $this->assertEquals(200, $response->getStatusCode());

        $response = $response->json();

        return $response['token'];
    }

    /**
     * Tests Creation
     *
     * @method POST
     */
    public function testCreate()
    {
        $data = array(
            'name'     => 'Unit_Sport_create'. time(),
            'isActive' => false,
        );

        $request = $this->client->createRequest('POST', 'sports', [
            'body' => $data,
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $this->token),
            ],
        ]);

        $response = $this->client->send($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('id', $response->json());
    }
}
