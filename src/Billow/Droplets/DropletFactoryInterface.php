<?php
namespace Billow\Droplets;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Droplets
 * @license http://opensource.org/licenses/MIT MIT
 */
interface DropletFactoryInterface
{
    /**
     * Method to retrieve a droplet from an Array of Data
     *
     * @param Array dropletInfo
     */
    public function getDroplet(Array $dropletInfo);
}
