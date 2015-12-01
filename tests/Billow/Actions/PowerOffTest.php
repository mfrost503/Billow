<?php
namespace Billow\Tests;
use Billow\Actions\PowerOff;
use PHPUnit_Framework_TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class PowerOffTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Billow\Actions\PowerOff
     */
    private $powerOff;

    /**
     * Test Setup
     */
    protected function setUp()
    {
        $this->powerOff = new PowerOff();
    }

    /**
     * Test teardown
     */
    protected function tearDown()
    {
        unset($this->powerOff);
    }

    /**
     * Ensure the request is created successfully
     */
    public function testEnsureRequestIsCreatedSuccessfully()
    {
        $this->powerOff->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer abced234d3ae'
        ];
        $expectedBody = json_encode(['type' => 'power_off']);
        $request = $this->powerOff->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getPath());
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
        $this->powerOff->getRequest();
    }
}

