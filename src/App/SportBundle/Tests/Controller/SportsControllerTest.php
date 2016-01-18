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
    /* @var string Base URI */
    protected $baseUri = 'http://api.sportroops.dev/v1/';

    /* @var string JWT */
    protected $token;

    /**
     * Creates an authenticated user.
     *
     * @param  Client $client
     * @param  array  $credentials
     *
     * @method POST
     *
     * @return string The Json Web Token
     */
    protected function createUser(Client $client, array $credentials)
    {
        $request = $client->createRequest('POST', 'login_check', ['body' => $credentials]);
        $response = $client->send($request);

        $this->assertEquals(200, $response->getStatusCode());

        $response = $response->json();
        $token = $response['token'];

        return $token;
    }

    /**
     * Tests Creation
     *
     * @method POST
     */
    public function testCreate()
    {
        $client = new Client([
            'base_url' => $this->baseUri,
        ]);

        $token = $this->createUser($client, [
            'email'    => 'admin',
            'password' => 'admin',
        ]);

        $data = array(
            'name'     => 'UnitTestSports_Create_fifre12344',
            'isActive' => false,
        );
        $request = $client->createRequest('POST', 'sports', [
            'body' => $data,
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $token),
            ],
        ]);

        $response = $client->send($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('id', $response->json());
    }
}
