<?php
namespace Billow\Tests;
use Billow\Actions\DisableBackups;
use PHPUnit\Framework\TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class DisableBackupsTest extends TestCase
{
    /**
     * @var \Billow\Actions\DisableBackups
     */
    private $disableBackups;

    /**
     * Test Setup
     */
    protected function setUp()
    {
        $this->disableBackups = new DisableBackups();
    }

    /**
     * Test tear down
     */
    protected function tearDown()
    {
        unset($this->disableBackups);
    }

    /**
     * Ensure the request is created successfully
     */
    public function testEnsureRequestIsCreatedSuccessfully()
    {
        $this->disableBackups->setId(12345);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer acded1232d3f3'
        ];
        $expectedBody = json_encode(['type' => 'disable_backups']);
        $request = $this->disableBackups->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12345/actions', $request->getUri()->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()['Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Ensure exception is thrown when ID is not set
     *
     * @expectedException \RuntimeException
     */
    public function testEnsureExceptionIsThrownForMissingId()
    {
        $this->disableBackups->getRequest();
    }
}
