<?php
namespace Billow\Tests;
use Billow\Actions\PasswordReset;
use PHPUnit\Framework\TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class PasswordResetTest extends TestCase
{
    /**
     * @var \Billow\Actions\PasswordReset
     */
    private $passwordReset;

    /**
     * Test set up method
     */
    protected function setUp()
    {
        $this->passwordReset = new PasswordReset();
    }

    /**
     * Test tear down method
     */
    protected function tearDown()
    {
        unset($this->passwordReset);
    }

    /**
     * Test to ensure request is created successfully
     */
    public function testEnsureRequestIsCreatedSuccessfully()
    {
        $this->passwordReset->setId(12358784234);
        $headers = [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer 12432adefa231'
        ];
        $expectedBody = json_encode(['type' => 'password_reset']);
        $request = $this->passwordReset->getRequest($headers);
        $this->assertEquals($expectedBody, $request->getBody()->getContents());
        $this->assertEquals('/v2/droplets/12358784234/actions', $request->getUri()->getPath());
        $this->assertContains($headers['Content-type'], $request->getHeaders()['Content-type']);
        $this->assertContains($headers['Authorization'], $request->getHeaders()['Authorization']);
    }

    /**
     * Ensure run time exception is throw if ID is not set
     *
     * @expectedException \RuntimeException
     */
    public function testEnsureRuntimeExceptionIsThrownForMissingId()
    {
        $this->passwordReset->getRequest();
    }
}
