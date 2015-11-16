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
     * Method of converting the Droplet Object to JSON
     *
     * @return string
     */
    public function toJSON();
}

