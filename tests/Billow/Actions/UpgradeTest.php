<?php
namespace Billow\Tests;
use Billow\Actions\Upgrade;
use PHPUnit\Framework\TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class UpgradeTest extends TestCase
{
    /**
     * @var \Billow\Actions\Upgrade
     */
    private $upgrade;

    /**
     * Test set up method
     */
    protected function setUp()
    {
        $this->upgrade = new Upgrade();
    }

    /**
     * Test tear down method
     */
    public function tearDown()
    {
        unset($this->upgrade);
    }

    /**
     * Ensure request is created successfully
     */
    public function testEnsureRequestCreatedSuccessfully()
    {
        $this->upgrade->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer 4123eaf532'
        ];
        $expectedBody = json_encode(['type' => 'upgrade']);
        $request = $this->upgrade->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getUri()->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()['Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Ensure runtime exception is throw when ID is not set
     *
     * @expectedException \RuntimeException
     */
    public function testEnsureRuntimeExceptionThrownWhenIdIsNotSet()
    {
        $this->upgrade->getRequest();
    }
}
