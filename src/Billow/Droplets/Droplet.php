<?php
namespace Billow\Droplets;
use RuntimeException;
/**
 * @author Matt Frost<mfrost.design@gmail.com
 * @package Billow
 * @license http://opensource.org/licenses/MIT MIT
 * @method validate ensure all required values are set
 * @method toJson represent the droplet as JSON
 * @method toArray represent the droplet as an Array
 * @method setImage set the image name
 */
abstract class Droplet 
{
    /**
     * ID for the droplet
     *
     * @var id
     */
    private $id;

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
     * Size slug of the droplet
     *
     * @var string size_slug
     */
    protected $size_slug;

    /**
     * Image info for the droplet 
     *
     * @var array image
     */
    protected $image;

    /**
     * Memory for the droplet
     *
     * @var int memory
     */
    protected $memory;

    /**
     * Virtual CPUS for the droplet
     *
     * @var int vcpus
     */
    protected $vcpus;

    /**
     * Disk size for the droplet
     *
     * @var int size
     */
    protected $disk;

    /**
     * Lock status of droplet
     *
     * @var bool locked
     */
    protected $locked;

    /**
     * Status of the droplet
     *
     * @var string status
     */
    protected $status;

    /**
     * Kernel info for the droplet
     *
     * @var array kernel
     */
    protected $kernel;

    /**
     * Creation Date of the droplet
     *
     * @var string created_at
     */
    protected $created_at;

    /**
     * Features available on the droplet
     *
     * @var array features
     */
    protected $features;

    /**
     * Backup IDs for the droplet
     *
     * @var array backup_ids
     */
    protected $backup_ids;

    /**
     * Snapshot IDs for the droplet
     *
     * @var array snapshot_ids
     */
    protected $snapshot_ids;

    /**
     * Networks the droplet resides on
     *
     * @var array networks
     */
    protected $networks;
    

    /**
     * Constructor - this is a value object, so values must be set
     * at the time of instantiation
     *
     * @params array $boxData
     */
    public function __construct(Array $boxData)
    {
        foreach ($boxData as $key => $value) {
            $this->$key = $value;
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
        return get_object_vars($this);
    }

    /**
     * PHP Magic method for handling property retrieval for non-existant
     * properties
     *
     * @param string $name
     * @return null
     */
    public function __get($name)
    {
        return null;
    }

    /**
     * PHP Magic method for handling propery assignment for non-existant
     * properties
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        // do not allow the setting of non-existant properties
    }
}
