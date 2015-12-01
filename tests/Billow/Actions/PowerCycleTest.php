<?php
namespace Billow\Tests;
use Billow\Actions\PowerCycle;
use PHPUnit_Framework_TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class PowerCycleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Billow\Actions\PowerCycle
     */
    private $powerCycle;

    /**
     * Test Setup
     */
    protected function setUp()
    {
        $this->powerCycle = new PowerCycle();
    }

    /**
     * Test teardown
     */
    protected function tearDown()
    {
        unset($this->powerCycle);
    }

    /**
     * Ensure the request is created successfully
     */
    public function testEnsureRequestIsCreatedSuccessfully()
    {
        $this->powerCycle->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer abced234d3ae'
        ];
        $expectedBody = json_encode(['type' => 'power_cycle']);
        $request = $this->powerCycle->getRequest($headers);
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
        $this->powerCycle->getRequest();
    }
}

