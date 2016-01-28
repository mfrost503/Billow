<?php
namespace Billow\Tests;
use Billow\Actions\Resize;
use PHPUnit_Framework_TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class ResizeTest extends PHPUnit_Framework_TestCase
{
    /**
     * Ensure request is create successfully with disk true
     */
    public function testEnsureRequestCreatedSuccessfullyWithDisk()
    {
        $params = [
            'size' => '40gb',
            'disk' => true
        ]; 
        $resize = new Resize($params);
        $resize->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer 1235a4eafed'
        ];
        $expectedBody = json_encode([
            'type' => 'resize',
            'disk' => true,
            'size' => '40gb'
        ]);
        $request = $resize->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()['Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Ensure request is create successfully with disk false
     */
    public function testEnsureRequestCreatedSuccessfullyWithoutDisk()
    {
        $params = [
            'size' => '40gb',
            'disk' => false
        ]; 
        $resize = new Resize($params);
        $resize->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer 1235a4eafed'
        ];
        $expectedBody = json_encode([
            'type' => 'resize',
            'disk' => false,
            'size' => '40gb'
        ]);
        $request = $resize->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()['Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Test to ensure RuntimeException is thrown if ID is not set
     *
     * @expectedException \RuntimeException
     */
    public function testEnsureRuntimeExceptionThrownWhenIdIsNull()
    {
        $params = [
            'size' => '40gb',
            'disk' => true
        ]; 
        $resize = new Resize($params);
        $resize->getRequest();
    }

    /**
     * Test to ensure argument exception is thrown if size isn't a string
     *
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidSizeProvider
     */
    public function testEnsureArgumentExceptionThrownForNonStringSize($size)
    {
        $resize = new Resize($size);
    }

    /**
     * Test to ensure argument exception is thrown if disk isn't a bool
     *
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidDiskProvider
     */
    public function testEnsureArgumentExceptionThrownForNonBoolDisk($disk)
    {
        $resize = new Resize($disk);
    }

    /**
     * Invalid Size Provider to pass multiple invalid parameter types to
     * the Resize constructor
     */
    public function invalidSizeProvider()
    {
        return [
            [['size' => new \Stdclass]],
            [['size' => 12345]],
            [['size' => true]],
            [['40gb']]
        ];
    }

    /**
     * Invalid Disk Provider to pass multiple invalid parameter types to
     * the resize constructor
     */
    public function invalidDiskProvider()
    {
        return [
            [['size' => '40gb', 'disk' => new \Stdclass]],
            [['size' => '40gb', 'disk' => 'true']],
            [['size' => '40gb', 'disk' => 12345]]
        ];
    }
}
