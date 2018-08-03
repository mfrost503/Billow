<?php
namespace Billow\Tests;
use Billow\Client;
use PHPUnit\Framework\TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class ClientTest extends TestCase
{
    /**
     * @var GuzzleHttp\Client
     */
    private $mockClient;

    /**
     * @var GuzzleHttp\Message\Response
     */
    private $mockResponse;

    /**
     * @var GuzzleHttp\Exceptions\RequestException
     */
    private $mockException;

    /**
     * Test Set up Method
     */
    protected function setUp()
    {
        $this->mockClient = $this->getMockBuilder('\GuzzleHttp\Client')
            ->setMethods(['get', 'post','send'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockResponse = $this->getMockBuilder('\GuzzleHttp\Psr7\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockException = $this->getMockBuilder('\GuzzleHttp\Exception\RequestException')
            ->setMethods(['hasResponse', 'getResponse', 'getMessage', 'getCode'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Test Tear Down Method
     */
    protected function tearDown()
    {
        unset($this->mockClient);
        unset($this->mockResponse);
        unset($this->mockException);
    }

    /**
     * Test to ensure http client can be set and retrieved
     */
    public function testEnsureHttpClientCanBeSetAndRetrieved()
    {
        $client = new Client();
        $client->setHttpClient($this->mockClient);
        $this->assertSame($this->mockClient, $client->getHttpClient());
    }

    /**
     * Test to ensure Http Client is created and returned if not set
     */
    public function testEnsureHttpClientIsCreatedAndReturnedIfNotSet()
    {
        $client = new Client();
        $httpClient = $client->getHttpClient();
        $this->assertInstanceOf('\GuzzleHttp\Client', $httpClient);
        $this->assertEquals($client::BASEURL, $httpClient->getConfig('base_url'));
    }

    /**
     * Test to ensure a get request works correctly
     */
    public function testEnsureGetRequestWorksCorrectly()
    {
        $url = 'droplets/12345';
        $options = ['headers' => ['Content-type' => 'application/json']];

        $this->mockClient->expects($this->once())
            ->method('get')
            ->with($url, $options)
            ->will($this->returnValue($this->mockResponse));

        $client = new Client();
        $client->setHttpClient($this->mockClient);
        $response = $client->get($url, $options);
        $this->assertInstanceOf('\GuzzleHttp\Psr7\Response', $response);
    }

    /**
     * Test to ensure a post request works correctly
     */
    public function testEnsurePostRequestWorksCorrectly()
    {
        $url = 'droplets/';
        $options = [
            'headers' => [
                'Content-type' => 'application/json'
            ],
            'body' => [
                'id' => 123,
                'name' => 'test box'
            ]
        ];
        $this->mockClient->expects($this->once())
            ->method('post')
            ->with($url, $options)
            ->will($this->returnValue($this->mockResponse));

        $client = new Client();
        $client->setHttpClient($this->mockClient);
        $response = $client->post($url, $options);
        $this->assertInstanceOf('\GuzzleHttp\Psr7\Response', $response);
    }

    /**
     * Test Ensure get request with RequestException rethrows exception
     *
     * @expectedException \GuzzleHttp\Exception\RequestException
     */
    public function testEnsureGetRequestWithExceptionReThrowsException()
    {
        $url = 'droplets/';
        $options = ['headers' => ['Content-type' => 'application/json']];
        $this->mockClient->expects($this->once())
            ->method('get')
            ->with($url, $options)
            ->will($this->throwException($this->mockException));
        $client = new Client();
        $client->setHttpClient($this->mockClient);
        $client->get($url, $options);
    }

    /**
     * Test Ensure a request with a different exception type is thrown as an exception
     *
     * @expectedException \Exception
     */
    public function testEnsureGetNonRequestExceptionIsThrownAsAnException()
    {
        $url = 'droplets/';
        $options = ['headers' => ['Content-type' => 'application/json']];
        $this->mockClient->expects($this->once())
            ->method('get')
            ->with($url, $options)
            ->will($this->throwException(new \Exception));
        $client = new Client();
        $client->setHttpClient($this->mockClient);
        $client->get($url, $options);
    }

    /**
     * Test Ensure post request with RequestException rethrows exception
     *
     * @expectedException \GuzzleHttp\Exception\RequestException
     */
    public function testEnsurePostRequestWithExceptionReThrowsException()
    {
        $url = 'droplets/';
        $options = ['headers' => ['Content-type' => 'application/json']];
        $this->mockClient->expects($this->once())
            ->method('post')
            ->with($url, $options)
            ->will($this->throwException($this->mockException));
        $client = new Client();
        $client->setHttpClient($this->mockClient);
        $client->post($url, $options);
    }

    /**
     * Test Ensure a request with a different exception type is thrown as an exception
     *
     * @expectedException \Exception
     */
    public function testEnsurePostNonRequestExceptionIsThrownAsAnException()
    {
        $url = 'droplets/';
        $options = ['headers' => ['Content-type' => 'application/json']];
        $this->mockClient->expects($this->once())
            ->method('post')
            ->with($url, $options)
            ->will($this->throwException(new \Exception));
        $client = new Client();
        $client->setHttpClient($this->mockClient);
        $client->post($url, $options);
    }

    /**
     * Test to ensure that the send method is being execute properly
     */
    public function testEnsureSendMethodWorksCorrectly()
    {
        $request = $this->getMockBuilder('\GuzzleHttp\Psr7\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockClient->expects($this->once())
            ->method('send')
            ->with($request)
            ->will($this->returnValue($this->mockResponse));

        $client = new Client();
        $client->setHttpClient($this->mockClient);
        $response = $client->send($request);
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $response);
        $this->assertSame($this->mockResponse, $response);
    }

    /**
     * Test to ensure that if a Request Exception is thrown that client rethrows it
     *
     * @expectedException \GuzzleHttp\Exception\RequestException
     */
    public function testEnsureSendRequestExceptionIsRethrown()
    {
        $request = $this->getMockBuilder('\GuzzleHttp\Psr7\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockClient->expects($this->once())
            ->method('send')
            ->with($request)
            ->will($this->throwException($this->mockException));

        $client = new Client();
        $client->setHttpClient($this->mockClient);
        $client->send($request);
    }

    /**
     * Test to ensure that if a Request Exception is thrown that client rethrows it
     *
     * @expectedException \Exception
     */
    public function testEnsureSendExceptionIsRethrown()
    {
        $request = $this->getMockBuilder('\GuzzleHttp\Psr7\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockClient->expects($this->once())
            ->method('send')
            ->with($request)
            ->will($this->throwException(new \Exception));

        $client = new Client();
        $client->setHttpClient($this->mockClient);
        $client->send($request);
    }
}
