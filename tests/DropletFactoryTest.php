<?php
namespace Billow\Tests;
use Billow\Droplets\DropletFactory;
use RuntimeException;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class DropletFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test to ensure that a Ubuntu box is returned when the provided
     * information denotes a ubuntu box
     */
    public function testEnsureUbuntuBoxIsReturned()
    {
        $dropletInfo = [
            'name' => 'Test Box',
            'size' => '40gb',
            'image' => 'ubuntu-test-image',
            'region' => 'nyc-3'
        ];
        $factory = new DropletFactory();
        $image = $factory->getDroplet($dropletInfo);
        $this->assertInstanceOf('\Billow\Droplets\Droplet', $image);
        $this->assertInstanceOf('\Billow\Droplets\Ubuntu', $image);
    }

    /**
     * Test to ensure that a missing image parameter throws an invalid argument exception
     *
     * @expectedException \InvalidArgumentException
     */
    public function testEnsureMissingImageValueThrowsException()
    {
        $dropletInfo = [
            'name' => 'Test Box',
            'size' => '40gb',
            'region' => 'nyc-3'
        ];
        $factory = new DropletFactory();
        $image = $factory->getDroplet($dropletInfo);
    }

    /**
     * Test to ensure that if an unsupported box is provided a runtime exception is thrown
     *
     * @expectedException \RuntimeException
     */
    public function testUnsupportedBoxThrowsException()
    {
        $dropletInfo = [
            'name' => 'Test Box',
            'size' => '40gb',
            'region' => 'nyc-3',
            'image' => 'unsupported-test-box'
        ];

        $factory = new DropletFactory();
        $image = $factory->getDroplet($dropletInfo);
    }
}
