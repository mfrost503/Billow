<?php
namespace Billow\Droplets;

/**
 * @author Matt Frost<mfrost.design@gmail.com
 * @package Billow
 * @subpackage Droplets
 * @license http://opensource.org/licenses/MIT MIT
 * @method toJson
 */
interface DropletInterface
{
    /**
     * Method to convert the Droplet Object to JSON
     *
     * @return string
     */
    public function toJSON();

    /**
     * Method to conver the Droplet Objecto to an Array
     *
     * @return Array
     */
    public function toArray();
}

