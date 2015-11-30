<?php
namespace Billow\Test;
use Billow\Actions\EnableIPv6;
use PHPUnit_Framework_TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class EnableIPv6Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Billow\Actions\EnableIPv6
     */
    private $enableIPv6;

    /**
     * Test set up method
     */
    protected function setUp()
    {
        $this->enableIPv6 = new EnableIPv6();
    }

    /**
     * Test tear down method
     */
    protected function tearDown()
    {
        unset($this->enableIPv6);
    }

    /**
     * Ensure request is created successfully
     */
    public function testEnsureRequestCreatedSuccessfully()
    {
        $this->enableIPv6->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer 123acdefb14085'
        ];
        $expectedBody = json_encode(['type' => 'enable_ipv6']);
        $request = $this->enableIPv6->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()[
'Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Ensure Runtime exception thrown if ID is not set
     *
     * @expectedException \RuntimeException
     */
    public function testEnsureRuntimeExceptionThrowIfIdNotSet()
    {
        $this->enableIPv6->getRequest();
    }
}
