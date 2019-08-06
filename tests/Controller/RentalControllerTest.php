<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RentalControllerTest
 * @package App\Tests\Controller
 */
class RentalControllerTest extends WebTestCase
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
     * @return mixed
     * @throws \Exception
     */
    public function testBookCar()
    {
        // create a new car
        $this->client->request(
            'POST',
            '/api/car/new',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ObjectData::getNewCarJson()
        );
        $response = $this->client->getResponse();
        $result = json_decode($response->getContent(), true);

        //book a car
        $carId = $result['id'];
        $this->client->request(
            'POST',
            '/api/rental/car/'.$carId.'/book',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            ObjectData::getCarBookingJson()
        );
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $result = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('price', $result);

        return $carId;
    }

    /**
     * test car availability checking
     *
     * @depends testBookCar
     * @param integer $id
     * @throws \Exception
     */
    public function testCheckAvailability($id)
    {
        $dates = json_decode(ObjectData::getCarBookingJson(), true);
        $url = '/api/rental/availability/car/'.$id.'?startDate='.$dates['startDate'].'&endDate='.$dates['endDate'];
        $this->client->request('GET', $url);

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $result = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('availability', $result);
        $this->assertEquals(false, $result['availability']);
    }
}
