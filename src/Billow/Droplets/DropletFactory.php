<?php
namespace Billow\Droplets;
use InvalidArgumentException;
use RuntimeException;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Droplets
 * @license http://opensource.org/licenses/MIT MIT
 */
class DropletFactory
{
    /**
     * Method to retrieve the proper type of Droplet based on parameter
     *
     * @param Array $dropletInfo 
     * @return \Billow\Droplets\DropletInterface
     */
    public function getDroplet(Array $dropletInfo)
    {
        if (!isset($dropletInfo['image'])) {
            throw new InvalidArgumentException('Image information not found in droplet info');
        }

        $image = $dropletInfo['image'];

        if (stristr($image, 'ubuntu')) {
            return new Ubuntu($dropletInfo);
        }

        throw new RuntimeException('There is no droplet matching the image provided in the droplet info');
    }
}
