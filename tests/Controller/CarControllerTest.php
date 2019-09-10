<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CarControllerTest
 * @package App\Tests\Controller
 */
class CarControllerTest extends WebTestCase
{
    /**
     * @var Client $client
     */
    protected $client;

    /**
     * set up client
     */
    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * test new car creation
     *
     * @return mixed
     */
    public function testNewCar()
    {
        $this->client->request(
            'POST',
            '/api/car/new',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ObjectData::getNewCarJson()
        );
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $result = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $result);

        return $result['id'];
    }

    /**
     * test get car detail
     *
     * @depends testNewCar
     * @param integer $id
     */
    public function testGetCar($id)
    {
        $this->client->request('GET', '/api/car/'.$id);
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $result = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $result);
    }

    /**
     * test car update
     *
     * @depends testNewCar
     * @param integer $id
     */
    public function testUpdateAction($id)
    {
        $this->client->request(
            'PUT',
            '/api/car/'.$id.'/update',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ObjectData::getCarUpdateJson()
        );
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $result = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('mileageExact', $result);
    }
}
