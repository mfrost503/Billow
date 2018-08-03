<?php
namespace Billow\Tests;
use Billow\Actions\PowerOn;
use PHPUnit\Framework\TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class PowerOnTest extends TestCase
{
    /**
     * @var \Billow\Actions\PowerOn
     */
    private $powerOn;

    /**
     * Test Setup
     */
    protected function setUp()
    {
        $this->powerOn = new PowerOn();
    }

    /**
     * Test teardown
     */
    protected function tearDown()
    {
        unset($this->powerOn);
    }

    /**
     * Ensure the request is created successfully
     */
    public function testEnsureRequestIsCreatedSuccessfully()
    {
        $this->powerOn->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer abced234d3ae'
        ];
        $expectedBody = json_encode(['type' => 'power_on']);
        $request = $this->powerOn->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getUri()->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()['Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Ensure runtime exception is thrown if ID is not set
     *
     * @expectedException \RuntimeException
     */
    public function testEnsureRuntimeExceptionIsThrownForMissingId()
    {
        $this->powerOn->getRequest();
    }
}

