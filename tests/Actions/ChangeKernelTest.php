<?php
namespace Billow\Tests;
use Billow\Actions\ChangeKernel;
use PHPUnit_Framework_TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class ChangeKernelTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test to ensure request created successfully with kernel id
     */
    public function testEnsureRequestCreatedSuccessfullyWithKernelId()
    {
        $changeKernel = new ChangeKernel(991);
        $changeKernel->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer 123aedfecb1286'
        ];
        $expectedBody = json_encode([
            'type' => 'change_kernel',
            'kernel' => 991
        ]);
        $request = $changeKernel->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()['Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Ensure Runtime Exception thrown if ID is not set
     *
     * @expectedException \RuntimeException
     */
    public function testEnsureRuntimeExceptionThrownForMissingId()
    {
        $changeKernel = new ChangeKernel(991);
        $changeKernel->getRequest();
    }

    /**
     * Ensure argument exception thrown for non-integer param
     *
     * @expectedException \InvalidArgumentException
     * @dataProvider kernelProvider
     */
    public function testEnsureArgumentExceptionThrownForBadParam($kernel)
    {
        $changeKernel = new ChangeKernel($kernel);
    }

    /**
     * Kernel provider to provide bad params
     */
    public function kernelProvider()
    {
        return [
            ['this is a test'],
            [true],
            [false],
            [new \Stdclass],
            [[1235]]
        ];
    }
}
