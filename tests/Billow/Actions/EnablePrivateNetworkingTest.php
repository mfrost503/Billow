<?php
namespace Billow\Tests;
use Billow\Actions\EnablePrivateNetworking;
use PHPUnit\Framework\TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class EnablePrivateNetworkingTest extends TestCase
{
    /**
     * @var \Billow\Actions\EnablePrivateNetworking
     */
    private $enablePrivateNetworking;

    /**
     * Test set up method
     */
    protected function setUp()
    {
        $this->enablePrivateNetworking = new EnablePrivateNetworking();
    }

    /**
     * Test tear down method
     */
    protected function tearDown()
    {
        unset($this->enablePrivateNetworking);
    }

    /**
     * Ensure request is created successfully
     */
    public function testEnsureRequestCreatedSuccessfully()
    {
        $this->enablePrivateNetworking->setId(12345);
        $headers = [
            'Content-type' => 'application/json',   
            'Authorization' => 'Bearer 123aedfcb8574'
        ];
        $expectedBody = json_encode(['type' => 'enable_private_networking']);
        $request = $this->enablePrivateNetworking->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getUri()->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()['Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Ensure Runtime Exception is throw if ID is not set
     *
     * @expectedException \RuntimeException
     */
    public function testEnsureRuntimeExceptionThrownWhenIdIsNotSet()
    {
        $this->enablePrivateNetworking->getRequest();
    }
}
