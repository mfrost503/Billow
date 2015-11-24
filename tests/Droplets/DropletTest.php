<?php
namespace Billow\Tests;
use PHPUnit_Framework_TestCase;
use Billow\DropletService;
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
     * @var \Billow\Droplets\Ubuntu
     */
    private $mockUbuntu;

    /**
     * @var \GuzzleHttp\Message\Response
     */
    private $mockResponse;

    /**
     * @var \Guzzle\Exception\RequestException
     */
    private $mockException;

    /**
     * @var string
     */
    private $ubuntuData;

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
            ->setMethods(['getStatusCode', 'getBody', 'hasResponse'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockException = $this->getMockBuilder('\GuzzleHttp\Exception\RequestException')
            ->setMethods(['hasResponse', 'getResponse', 'getCode'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->ubuntuData = file_get_contents('tests/fixtures/ubuntu-retrieve-droplet-response.json');
    }

    /**
     * Tear Down Method
     */
    protected function tearDown()
    {
        unset($this->mockClient);
        unset($this->mockUbuntu); 
        unset($this->mockResponse);
        unset($this->mockException);
        unset($this->ubuntuData);
    }

    /**
     * Test to ensure that set client works correctly, confirmed
     * by getClient returning the same mock that was set
     */
    public function testEnsureSetGetClientWorksCorrectly()
    {
        $droplet = new DropletService();
        $droplet->setClient($this->mockClient);
        $client = $droplet->getClient();
        $this->assertSame($this->mockClient, $client, 'The returned client was not the same as the mock client');
    }

    /**
     * Test to ensure that getClient returns a new Client object if one has not been previously set
     */
    public function testEnsureGetClientReturnsNewClientInstance()
    {
        $droplet = new DropletService();
        $client = $droplet->getClient();
        $this->assertInstanceof('\Billow\Client', $client, 'The returned client is not a type \Billow\Client');
    }

    /**
     * Test to ensure the create method works correctly
     */
    public function testEnsureCreateCommandWorksCorrectly()
    {
        $request = [
            'name' => 'Test Droplet',
            'size' => '2gb',
            'region' => 'nyc1'
        ];

        $headers = ['Content-type' => 'application/json', 'Authorization' => 'Bearer 123456'];
        $client_params =[
            'headers' => $headers,
            'body' => json_encode($request)
        ];

        $this->mockClient->expects($this->once())
            ->method('post')
            ->with('droplets', $client_params)
            ->will($this->returnValue($this->mockResponse));

        $droplet = new DropletService();
        $droplet->setClient($this->mockClient);
        $response = $droplet->create($request, $headers);
        $this->assertSame($response, $this->mockResponse);
    }

    /**
     * Ensure Content-type header is automatically added if it's not provided
     * in the initial payload
     */
    public function testEnsureContentTypeIsAddedWhenNotProvidedExplicitly()
    {
         $request = [
            'name' => 'Test Droplet',
            'size' => '2gb',
            'region' => 'nyc1'
        ];

        $headers = ['Authorization' => 'Bearer 123456'];
        $autoPopulatedHeaders = $headers;
        $autoPopulatedHeaders['Content-type'] = 'application/json'; 

        $client_params =[
            'headers' => $autoPopulatedHeaders,
            'body' => json_encode($request)
        ];

        $this->mockClient->expects($this->once())
            ->method('post')
            ->with('droplets', $client_params)
            ->will($this->returnValue($this->mockResponse));

        $droplet = new DropletService();
        $droplet->setClient($this->mockClient);
        $response = $droplet->create($request, $headers);
        $this->assertSame($response, $this->mockResponse);
   }

    /**
     * Test to ensure exception is handled correctly when it has a response
     *
     * @expectedException \Billow\Exceptions\ProvisionException
     */
    public function testEnsureExceptionWithResponseIsHandled()
    {
        $request = [
            'name' => 'Test Droplet',
            'size' => '2gb',
            'region' => 'nyc1'
        ];

        $headers = ['Authorization' => 'Bearer 123456'];
        $autoPopulatedHeaders = $headers;
        $autoPopulatedHeaders['Content-type'] = 'application/json'; 

        $client_params =[
            'headers' => $autoPopulatedHeaders,
            'body' => json_encode($request)
        ];

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

        $droplet = new DropletService();
        $droplet->setClient($this->mockClient);
        $response = $droplet->create($request, $headers);
        $this->assertEquals(401, $response->getStatusCode(), 'Status code mismatched');
        $this->assertInstanceOf('\GuzzleHttp\Message\Response', $response, 'Exception did not return a response');
    }

    /**
     * Test to ensure exception is handled correctly when there is no response
     *
     * @expectedException \Billow\Exceptions\ProvisionException
     * @expectedExceptionMessage Failed to provision new droplet
     */
    public function testEnsureExceptionIsHandledCorrectlyWithNoResponse()
    {
        $request = [
            'name' => 'Test Droplet',
            'size' => '2gb',
            'region' => 'nyc1'
        ];

        $headers = ['Authorization' => 'Bearer 123456'];
        $autoPopulatedHeaders = $headers;
        $autoPopulatedHeaders['Content-type'] = 'application/json'; 

        $client_params =[
            'headers' => $autoPopulatedHeaders,
            'body' => json_encode($request)
        ];

        $this->mockException->expects($this->once())
            ->method('hasResponse')
            ->will($this->returnValue(false));

        $this->mockClient->expects($this->once())
            ->method('post')
            ->with('droplets', $client_params)
            ->will($this->throwException($this->mockException));

        $droplet = new DropletService();
        $droplet->setClient($this->mockClient);
        $response = $droplet->create($request, $headers);
    }       

    /**
     * Test to ensure that a box can be retrieved
     */
    public function testEnsureABoxCanBeRetrieved()
    {
        $id = 5671232;
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer 12345'
        ];

        $this->mockResponse->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($this->ubuntuData));
        $this->mockClient->expects($this->once())
            ->method('get')
            ->with('droplets/' . $id, ['headers' => $headers])
            ->will($this->returnValue($this->mockResponse));

        $service = new DropletService();
        $service->setClient($this->mockClient);
        $box = $service->retrieve($id, $headers);
        $this->assertInstanceOf('\Billow\Droplets\Ubuntu', $box);
    }

    /**
     * Test to ensure that on retrieval error Droplet Exception is thrown
     *
     * @expectedException \Billow\Exceptions\DropletException
     */
    public function testEnsureDropletExceptionIsThrowOnRequestException()
    {
        $id = 5671232;
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer 12345'
        ];

        $this->mockResponse->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('Bad Request'));
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
            ->method('get')
            ->with('droplets/' . $id, ['headers' => $headers])
            ->will($this->throwException($this->mockException));

        $service = new DropletService();
        $service->setClient($this->mockClient);
        $service->retrieve($id, $headers);
    }

    /**
     * Test to ensure when exception has no response that a Droplet Exeception
     * is thrown
     *
     * @expectedException \Billow\Exceptions\DropletException
     * @expectedExceptionMessage Retrieval of droplet failed
     */
    public function testExceptionWithNoResponseThrowsDropletException()
    { 
        $id = 5234324;
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer 12345'
        ];

        $this->mockException->expects($this->once())
            ->method('hasResponse')
            ->will($this->returnValue(false));

        $this->mockClient->expects($this->once())
            ->method('get')
            ->with('droplets/' . $id, ['headers' => $headers])
            ->will($this->throwException($this->mockException));

        $service = new DropletService();
        $service->setClient($this->mockClient);
        $service->retrieve($id, $headers);
    }
}
