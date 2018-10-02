<?php
namespace Billow\Tests;
use Billow\Actions\Reboot;
use Billow\Actions\ActionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @package Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class ActionTest extends TestCase
{
    /**
     * @var \Billow\Actions\Resize
     */
    private $action;

    /**
     * Test setup
     */
    protected function setUp()
    {
        $this->action = new Reboot();
    }

    /**
     * Test Teardown
     */
    protected function tearDown()
    {
        unset($this->action);
    }

    /**
     * Test to ensure that the getRequest method works correctly
     */
    public function testEnsureGetRequestWorksCorrectly()
    {
        $this->action->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer aba234cde34234'
        ];
        $body = json_encode(['type' => 'testAction']);
        $request = $this->action->getRequest($headers, $body);
        $requestHeaders = $request->getHeaders();
        $this->assertEquals($body, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getUri()->getPath());
        $this->assertContains($headers['Content-type'], $requestHeaders['Content-type']);
        $this->assertContains($headers['Authorization'], $requestHeaders['Authorization']);
    } 

    /**
     * Ensure if no ID is set, that a runtime exception is thrown
     *
     * @expectedException \RuntimeException
     */
    public function testEnsureExceptionThrownWhenIdIsNotSet()
    {
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer aba234cde34234'
        ];
        $body = json_encode(['type' => 'testAction']);
        $request = $this->action->getRequest($headers, $body);
    }    
}
