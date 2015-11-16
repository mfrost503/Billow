<?php
namespace Services\DigitalOcean\Droplets;

/**
 * @author Matt Frost<mattf@budgetdumpster.com
 * @package Services
 * @subpackage DigitalOcean
 * @subpackage Droplets
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

