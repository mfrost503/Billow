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
     * Ubuntu Droplet Data
     *
     * @var array $ubuntuData
     */
    private $ubuntuData;

    /**
     * Test Setup
     */
    protected function setUp()
    {
        $data = file_get_contents('tests/Billow/fixtures/ubuntu-retrieve-droplet-response.json');
        $this->ubuntuData = json_decode($data, true);
    }

    /**
     * Test Tear Down
     */
    protected function tearDown()
    {
        unset($this->ubuntuData);
    }

    /**
     * Test to ensure that a Ubuntu box is returned when the provided
     * information denotes a ubuntu box
     */
    public function testEnsureUbuntuBoxIsReturned()
    {
        $factory = new DropletFactory();
        $image = $factory->getDroplet($this->ubuntuData['droplet']);
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
        $data = $this->ubuntuData;
        unset($data['droplet']['image']);
        $factory = new DropletFactory();
        $image = $factory->getDroplet($data);
    }

    /**
     * Test to ensure that if an unsupported box is provided a runtime exception is thrown
     *
     * @expectedException \InvalidArgumentException
     */
    public function testUnsupportedBoxThrowsException()
    {
        $data = $this->ubuntuData;
        unset($data['droplet']['image']);
        $data['droplet']['image'] = [
            'id' => 12314324,
            'name' => 'Non Existant Box',
            'distribution' => 'Non Existant',
            'slug' => 'non-existant-box',
            'public' => true,   
            'regions' => [
                'nyc3',
                'nyc2',
                'nyc1',
                'sfo1',
                'ams1'
            ]
        ];
        $factory = new DropletFactory();
        $image = $factory->getDroplet($data);
    }

    /**
     * Test ensure that all the boxes are created correctly
     *
     * @dataProvider dropletProvider
     */
    public function testEnsureAllBoxTypesAreCreatedCorrectly($distribution, $expectedType)
    {
        $factory = new DropletFactory();
        $data = $this->ubuntuData;
        $data['droplet']['image']['distribution'] = $distribution;
        $droplet = $factory->getDroplet($data['droplet']);
        $this->assertInstanceOf($expectedType, $droplet);
    }  

    /**
     * Test ensure that a runtime exception is thrown if an unknown distro is provided
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage There is no droplet matching the image provided in the droplet info
     */
    public function testEnsureRuntimeExceptionIsThrownForUnknownDistro()
    {
        $factory = new DropletFactory();
        $data = $this->ubuntuData;
        $data['droplet']['image']['distribution'] = 'Made up distro';
        $droplet = $factory->getDroplet($data['droplet']);
    }

    /**
     * The Droplet Provider to provide distributions and expected types
     */
    public function dropletProvider()
    {
        return [
            ['fedora', '\Billow\Droplets\Fedora'],
            ['debian', '\Billow\Droplets\Debian'],
            ['centos', '\Billow\Droplets\CentOS'],
            ['coreos', '\Billow\Droplets\CoreOS'],
            ['freebsd','\Billow\Droplets\FreeBSD']
        ];
    }
}
