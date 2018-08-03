<?php
namespace Billow\Tests;
use Billow\Droplets\Ubuntu;
use Billow\Droplets\DropletFactory;
use PHPUnit\Framework\TestCase;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Tests
 * @license http://opensource.org/licenses/MIT MIT
 */
class DropletTest extends TestCase
{
    /**
     * @var string
     */
    private $ubuntuData;

    /**
     * @var \Billow\Droplets\Ubuntu
     */
    private $droplet;

    /**
     * Test Setup method
     */
    protected function setUp()
    {
        $data = file_get_contents('tests/Billow/fixtures/ubuntu-retrieve-droplet-response.json');
        $this->ubuntuData = json_decode($data, true);
        $this->droplet = new Ubuntu($this->ubuntuData['droplet']);
    }

    /**
     * Test Tear Down method
     */
    protected function tearDown()
    {
        unset($this->ubuntuData);
        unset($this->droplet);
    }

    /**
     * Ensure a Ubuntu Box is created
     */
    public function testEnsureUbuntuBoxIsCreated()
    {
        $droplet = new Ubuntu($this->ubuntuData['droplet']);
        $this->assertInstanceOf('\Billow\Droplets\Ubuntu', $droplet);
    }

    /**
     * Ensure toArray returns an array of the box
     */
    public function testEnsureToArrayReturnsMatchingArray()
    {
        $array = $this->droplet->toArray();
        $this->assertEquals($this->ubuntuData['droplet'], $array);
    }        

    /**
     * Ensure toJson returns a valid json string of the box
     */
    public function testEnsureToJsonReturnsMatchingJsonString()
    {
        $json = $this->droplet->toJSON();
        $expectedJSON = json_encode($this->ubuntuData['droplet']);
        $array = json_decode($json, true);
        $this->assertEquals($this->ubuntuData['droplet']['region'], $array['region']);
        $this->assertEquals($this->ubuntuData['droplet']['networks'], $array['networks']);
        $this->assertEquals($this->ubuntuData['droplet']['id'], $array['id']);
        $this->assertEquals($this->ubuntuData['droplet']['name'], $array['name']);
        $this->assertEquals($this->ubuntuData['droplet']['size'], $array['size']);
        $this->assertEquals($this->ubuntuData['droplet']['size_slug'], $array['size_slug']);
        $this->assertEquals($this->ubuntuData['droplet']['image'], $array['image']);
        $this->assertEquals($this->ubuntuData['droplet']['memory'], $array['memory']);
        $this->assertEquals($this->ubuntuData['droplet']['vcpus'], $array['vcpus']);
        $this->assertEquals($this->ubuntuData['droplet']['disk'], $array['disk']);
        $this->assertEquals($this->ubuntuData['droplet']['locked'], $array['locked']);
        $this->assertEquals($this->ubuntuData['droplet']['status'], $array['status']);
        $this->assertEquals($this->ubuntuData['droplet']['kernel'], $array['kernel']);
        $this->assertEquals($this->ubuntuData['droplet']['created_at'], $array['created_at']);
        $this->assertEquals($this->ubuntuData['droplet']['features'], $array['features']);
        $this->assertEquals($this->ubuntuData['droplet']['backup_ids'], $array['backup_ids']);
        $this->assertEquals($this->ubuntuData['droplet']['snapshot_ids'], $array['snapshot_ids']);
    }

    /**
     * Test to make sure a property cannot be set or retrieved
     */
    public function testEnsureNewPropertyCannotBeSetOrRetrieved()
    {
        $this->droplet->testProperty = 'test';
        $this->assertNull($this->droplet->testProperty);
        $array = get_object_vars($this->droplet);
        $this->assertFalse(isset($array['testProperty']));
    }
}
