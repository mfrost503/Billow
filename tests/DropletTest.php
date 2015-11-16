<?php
namespace Billow\Tests;
use PHPUnit_Framework_TestCase;
use Billow\Droplet;
use Billow\Droplets\Ubuntu;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\Response;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Billow
 * @subpackage Tests
 */
class DropletTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Billow\Client $mockClient
     */
    private $mockClient;

    /**
     * Setup Method
     */
    protected function setUp()
    {
        $this->mockClient = $this->getMock('\Billow\Client', ['get', 'post']);
        $this->mockUbuntu = $this->getMockBuilder('\Billow\Droplets\Ubuntu')
            ->setMethods(['toJson'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockResponse = $this->getMockBuilder('\GuzzleHttp\Message\Response')
            ->setMethods(['getStatusCode'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockException = $this->getMockBuilder('\GuzzleHttp\Exception\RequestException')
            ->setMethods(['hasResponse', 'getResponse', 'getCode'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Tear Down Method
     */
    protected function tearDown()
    {
        unset($this->mockClient);
        unset($this->mockUbuntu); 
        unset($this->mockResponse);
    }

    /**
     * Test to ensure that set client works correctly, confirmed
     * by getClient returning the same mock that was set
     */
    public function testEnsureSetGetClientWorksCorrectly()
    {
        $droplet = new Droplet();
        $droplet->setClient($this->mockClient);
        $client = $droplet->getClient();
        $this->assertSame($this->mockClient, $client, 'The returned client was not the same as the mock client');
    }

    /**
     * Test to ensure that getClient returns a new Client object if one has not been previously set
     */
    public function testEnsureGetClientReturnsNewClientInstance()
    {
        $droplet = new Droplet();
        $client = $droplet->getClient();
        $this->assertInstanceof('\Billow\Client', $client, 'The returned client is not a type \Billow\Client');
    }

    /**
     * Test to ensure the create method works correctly
     */
    public function testEnsureCreateCommandWorksCorrectly()
    {
        $json = json_encode([
            'name' => 'Test Droplet',
            'size' => '2gb',
            'region' => 'nyc1'
        ]);

        $headers = ['Content-type' => 'application/json', 'Authorization' => 'Bearer 123456'];
        $client_params =[
            'headers' => $headers,
            'body' => $json
        ];

        $this->mockUbuntu->expects($this->once())
            ->method('toJson')
            ->will($this->returnValue($json));

        $this->mockClient->expects($this->once())
            ->method('post')
            ->with('droplets', $client_params)
            ->will($this->returnValue($this->mockResponse));

        $droplet = new Droplet();
        $droplet->setClient($this->mockClient);
        $response = $droplet->create($this->mockUbuntu, $headers);
        $this->assertSame($response, $this->mockResponse);
    }

    /**
     * Ensure Content-type header is automatically added if it's not provided
     * in the initial payload
     */
    public function testEnsureContentTypeIsAddedWhenNotProvidedExplicitly()
    {
         $json = json_encode([
            'name' => 'Test Droplet',
            'size' => '2gb',
            'region' => 'nyc1'
        ]);

        $headers = ['Authorization' => 'Bearer 123456'];
        $autoPopulatedHeaders = $headers;
        $autoPopulatedHeaders['Content-type'] = 'application/json'; 

        $client_params =[
            'headers' => $autoPopulatedHeaders,
            'body' => $json
        ];

        $this->mockUbuntu->expects($this->once())
            ->method('toJson')
            ->will($this->returnValue($json));

        $this->mockClient->expects($this->once())
            ->method('post')
            ->with('droplets', $client_params)
            ->will($this->returnValue($this->mockResponse));

        $droplet = new Droplet();
        $droplet->setClient($this->mockClient);
        $response = $droplet->create($this->mockUbuntu, $headers);
        $this->assertSame($response, $this->mockResponse);
   }

    /**
     * Test to ensure exception is handled correctly when it has a response
     */
    public function testEnsureExceptionWithResponseIsHandled()
    {
        $json = json_encode([
            'name' => 'Test Droplet',
            'size' => '2gb',
            'region' => 'nyc1'
        ]);

        $headers = ['Authorization' => 'Bearer 123456'];
        $autoPopulatedHeaders = $headers;
        $autoPopulatedHeaders['Content-type'] = 'application/json'; 

        $client_params =[
            'headers' => $autoPopulatedHeaders,
            'body' => $json
        ];

        $this->mockUbuntu->expects($this->once())
            ->method('toJson')
            ->will($this->returnValue($json));

        $this->mockResponse->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue(401));

        $this->mockException->expects($this->once())
            ->method('hasResponse')
            ->will($this->returnValue(true));

        $this->mockException->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($this->mockResponse));

        $this->mockClient->expects($this->once())
            ->method('post')
            ->with('droplets', $client_params)
            ->will($this->throwException($this->mockException));

        $droplet = new Droplet();
        $droplet->setClient($this->mockClient);
        $response = $droplet->create($this->mockUbuntu, $headers);
        $this->assertEquals(401, $response->getStatusCode(), 'Status code mismatched');
        $this->assertInstanceOf('\GuzzleHttp\Message\Response', $response, 'Exception did not return a response');
    }

    /**
     * Test to ensure exception is handled correctly when there is no response
     */
    public function testEnsureExceptionIsHandledCorrectlyWithNoResponse()
    {
        $json = json_encode([
            'name' => 'Test Droplet',
            'size' => '2gb',
            'region' => 'nyc1'
        ]);

        $headers = ['Authorization' => 'Bearer 123456'];
        $autoPopulatedHeaders = $headers;
        $autoPopulatedHeaders['Content-type'] = 'application/json'; 

        $client_params =[
            'headers' => $autoPopulatedHeaders,
            'body' => $json
        ];

        $this->mockUbuntu->expects($this->once())
            ->method('toJson')
            ->will($this->returnValue($json));

        $this->mockException->expects($this->once())
            ->method('hasResponse')
            ->will($this->returnValue(false));

        $this->mockClient->expects($this->once())
            ->method('post')
            ->with('droplets', $client_params)
            ->will($this->throwException($this->mockException));

        $droplet = new Droplet();
        $droplet->setClient($this->mockClient);
        $response = $droplet->create($this->mockUbuntu, $headers);

        // if a GuzzleHttp\Exception\RequestException does not have a response, it returns 0
        $this->assertEquals(0, $response->getStatusCode(), 'Status code mismatched');
        $this->assertInstanceOf('\GuzzleHttp\Message\Response', $response, 'Exception did not return a response');
    }       
}
