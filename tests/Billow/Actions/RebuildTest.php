<?php
namespace Billow\Tests;
use Billow\Actions\Rebuild;
use PHPUnit_Framework_TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class RebuildTest extends PHPUnit_Framework_TestCase
{
    /**
     * Ensure request is created successfully with integer
     */
    public function testEnsureRequestCreatedSuccessfullyWithInt()
    {
        $rebuild = new Rebuild(12352343);
        $rebuild->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer 123adef234f23'
        ];
        $expectedBody = json_encode([
            'type' => 'rebuild',
            'image' => 12352343
        ]);
        $request = $rebuild->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()['Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Ensure request is created successfully with string
     */
    public function testEnsureRequestCreatedSuccessfullyWithString()
    {
        $rebuild = new Rebuild('14-04-x64');
        $rebuild->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer 1234a34fefdacd'
        ];
        $expectedBody = json_encode([
            'type' => 'rebuild',
            'image' => '14-04-x64'
        ]);
        $request = $rebuild->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()['Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Ensure runtime exception thrown if ID is not set
     *
     * @expectedException \RuntimeException
     */
    public function testRuntimeExceptionThrownWhenIdIsBlank()
    {
        $rebuild = new Rebuild(1235355);
        $rebuild->getRequest();
    }

    /**
     * Ensure argument exception thrown if image argument isn't string
     * or numeric
     *
     * @dataProvider imageProvider
     * @expectedException \InvalidArgumentException
     */
    public function testArgumentExceptionThrownIfImageIsWrongType($image)
    {
        $rebuild = new Rebuild($image);
    }

    /**
     * Image Provider to provide invalid types
     */
    public function imageProvider()
    {
        return [
            [new \Stdclass],
            [true],
            [false],
            [['123', 123, '14-04-x64']]
        ];
    }
}
