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
        $data = file_get_contents('tests/fixtures/ubuntu-retrieve-droplet-response.json');
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
        $image = $factory->getDroplet($this->ubuntuData);
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
     * @expectedException \RuntimeException
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
}
