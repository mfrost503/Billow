<?php
namespace Billow\Tests;
use Billow\Actions\CreateSnapshot;
use PHPUnit\Framework\TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class CreateSnapshotTest extends TestCase
{
    /**
     * Ensure request is created successfully
     */
    public function testEnsureRequestCreatedSuccessfully()
    {
        $params = ['name' => 'my-snapshot'];
        $createSnapshot = new CreateSnapshot($params);
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
        $this->assertEquals('/v2/droplets/12345/actions', $request->getUri()->getPath());
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
        $params = ['name' => 'my-snapshot'];
        $createSnapshot = new CreateSnapshot($params);
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
            [['name' => true]],
            [['name' => false]],
            [['name' => 12345]],
            [['name' => new \Stdclass]],
            [['my-snapshot']]
        ];
    }
}
