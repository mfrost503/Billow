<?php
namespace Billow\Tests;
use Billow\Actions\Restore;
use PHPUnit\Framework\TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class RestoreTest extends TestCase
{
    /**
     * Ensure the request is created successfully with integer
     */
    public function testEnsureRequestIsCreatedSuccessfully()
    {
        $params = ['image' => 51234];
        $restore = new Restore($params);
        $restore->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer abced234d3ae'
        ];
        $expectedBody = json_encode([
            'type' => 'enable_backups',
            'image' => 51234
        ]);
        $request = $restore->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getUri()->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()['Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Ensure the request is created successfully with slug 
     */
    public function testEnsureRequestIsCreatedSuccessfullyWithSlug()
    {
        $restore = new Restore(['image' => '14-04-x64']);
        $restore->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer abced234d3ae'
        ];
        $expectedBody = json_encode([
            'type' => 'enable_backups',
            'image' => '14-04-x64' 
        ]);
        $request = $restore->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getUri()->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()['Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Ensure Invalid argument exception is throw when non-numeric
     * or non-string parameter is passed
     *
     * @expectedException \InvalidArgumentException
     */
    public function testEnsureInvalidArgumentExceptionIsThrown()
    {
        $restore = new Restore(['image' => new \Stdclass]);
        $restore->setId(12345);
        $restore->getRequest();
    }
 
    /**
     * Ensure runtime exception is thrown if ID is not set
     *
     * @expectedException \RuntimeException
     */
    public function testEnsureRuntimeExceptionIsThrownForMissingId()
    {
        $restore = new Restore(['image' => 12345]);
        $restore->getRequest();
    }

    /**
     * Ensure InvalidArgumentException is thrown if image key is not set
     *
     * @expectedException \InvalidArgumentException
     */
    public function testMissingImageThrowsException()
    {
        $restore = new Restore([12345]);
        $restore->getRequest();
    }
}

