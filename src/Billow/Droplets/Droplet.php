<?php
namespace Services\DigitalOcean\Droplets;
use RuntimeException;
/**
 * @author Matt Frost<mattf@budgetdumpster.com
 * @package Services
 * @subpackage DigitalOcean
 * @subpackage Droplets
 */
abstract class Droplet 
{
    /**
     * Name for the droplet
     *
     * @var string name
     */
    protected $name;

    /**
     * Region slug in which to deploy the box
     *
     * @var string region
     */
    protected $region;

    /**
     * Size of the box
     *
     * @var string size
     */
    protected $size;

    /**
     * Operating system image to use load on the droplet
     *
     * @var string image
     */
    protected $image = '';

    /**
     * SSH Keys to load on the droplet
     *
     * @var array sshKeys
     */
    protected $sshKeys = [];

    /**
     * Boolean value for whether or not the server should have backups
     *
     * @var boolean backups
     */
    protected $backups = false;

    /**
     * Boolean value for whether the server should use IPv6
     *
     * @var boolean $ipv6
     */
    protected $ipv6 = false;

    /**
     * Boolean value for whether private networking should be used
     * on the droplet (only in certain regions)
     *
     * @var boolean privateNetworking
     */
    protected $privateNetworking = false;

    /**
     * A string of user data that can be used on a droplet that
     * has metadata as a feature
     *
     * @var string $userData
     */
    protected $userData = '';

    /**
     * An array of the required fields to create a droplet
     *
     * @var array $requiredFields
     */
    protected $requiredFields = [
        'name',
        'region',
        'size',
    ];

    /**
     * Constructor - this is a value object, so values must be set
     * at the time of instantiation
     *
     * @params array $boxData
     */
    public function __construct(Array $boxData)
    {
        $this->validate($boxData);
    }

    /**
     * Validate that all the required fields are present and not empty
     *
     * @param array $boxData
     */
    protected function validate(Array $boxData)
    {
        array_walk($this->requiredFields, function($value, $key) use ($boxData){
            if (!array_key_exists($value, $boxData)) {
                throw new RuntimeException('Required field: ' . $value . ' not present');
            }

            if (empty($boxData[$value]) || $boxData[$value] === "" || $boxData[$value] === null) {
                throw new RuntimeException('Required field: ' . $value . ' has no value');
            }
        }); 
        $this->name = $boxData['name'];
        $this->region = $boxData['region'];
        $this->size = $boxData['size'];

        if (isset($boxData['sshKeys']) && is_array($boxData['sshKeys'])) {
            $this->sshKeys = $boxData['sshKeys'];
        }

        if (isset($boxData['backups']) && is_bool($boxData['backups'])) {
            $this->backups = $boxData['backups'];
        }

        if (isset($boxData['ipv6']) && is_bool($boxData['ipv6'])) {
            $this->ipv6 = $boxData['ipv6'];
        }

        if (isset($boxData['userData']) && is_string($boxData['userData'])) {
            $this->userData = $boxData['userData'];
        }

        if (isset($boxData['privateNetworking']) && is_bool($boxData['privateNetworking'])) {
            $this->privateNetworking = $boxData['privateNetworking']; 
        }
    }

    /**
     * Method to represent the box as JSON
     *
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this->toArray()); 
    }

    /**
     * Method to represent the box as an Array
     *
     * @return Array
     */
    public function toArray()
    {
        $data = [
            'name' => $this->name,
            'region' => $this->region,
            'size' => $this->size,
            'image' => $this->image,
            'ssh_keys' => $this->sshKeys,
            'backups' => $this->backups,
            'ipv6' => $this->backups,
            'user_data' => $this->userData,
            'private_networking' => $this->privateNetworking
        ];
        return $data;
    }

    /**
     * Method used to set image slug
     *
     * @param string $image 
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
}
