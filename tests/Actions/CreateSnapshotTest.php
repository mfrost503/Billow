<?php
namespace Billow\Tests;
use Billow\Actions\CreateSnapshot;
use PHPUnit_Framework_TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class CreateSnapshotTest extends PHPUnit_Framework_TestCase
{
    /**
     * Ensure request is created successfully
     */
    public function testEnsureRequestCreatedSuccessfully()
    {
        $createSnapshot = new CreateSnapshot('my-snapshot');
        $createSnapshot->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer 123afedf3434140984'
        ];
        $expectedBody = json_encode([
            'type' => 'snapshot',
            'name' => 'my-snapshot'
        ]);
        $request = $createSnapshot->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()['Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Ensure run time exception is thrown if ID is not set
     *
     * @expectedException \RuntimeException
     */
    public function testEnsureRuntimeExceptionThrownWhenIdIsNotSet()
    {
        $createSnapshot = new CreateSnapshot('my-snapshot');
        $createSnapshot->getRequest();
    }

    /**
     * Ensure argument exception is thrown for bad arguments
     *
     * @expectedException \InvalidArgumentException
     * @dataProvider snapshotProvider
     */
    public function testEnsureArgumentExceptionThrownForBadArgs($name)
    {
        $createSnapshot = new CreateSnapshot($name);
    }

    /**
     * Snapshot provider to provide bad arguments
     */
    public function snapshotProvider()
    {
        return [
            [true],
            [false],
            [['my-snapshot']],
            [12345],
            [new \Stdclass]
        ];
    }
}
