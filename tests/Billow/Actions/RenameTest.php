<?php
namespace Billow\Tests;
use Billow\Actions\Rename;
use PHPUnit\Framework\TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class RenameTest extends TestCase
{
    /**
     * Ensure request is created successfully with a new name
     */
    public function testEnsureRequestCreatedSuccessfullyWithName()
    {
        $rename = new Rename(['name' => 'my-renamed-server']);
        $rename->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer 123adefac235'
        ];
        $expectedBody = json_encode([
            'type' => 'rename',
            'name' => 'my-renamed-server'
        ]);
        $request = $rename->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getUri()->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()['Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Ensure Runtime Exception thrown if ID is not set
     *
     * @expectedException \RuntimeException
     */
    public function testEnsureRuntimeExceptionThrownIfIdNotSet()
    {
        $rename = new Rename(['name' => 'my-renamed-server']);
        $rename->getRequest();
    }

    /**
     * Ensure argument exception thrown for non-string parameter
     *
     * @expectedException \InvalidArgumentException
     * @dataProvider nameProvider
     */
    public function testEnsureArgumentExceptionThrownForBadType($name)
    {
        $rename = new Rename($name);
    }

    /**
     * Name Provider used to provide bad types
     */
    public function nameProvider()
    {
        return [
            [['name' => 12345]],
            [[new \Stdclass]],
            [['12345']],
            [[true]],
            [[false]]
        ];
    }
}
