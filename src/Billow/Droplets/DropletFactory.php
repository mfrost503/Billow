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
class DropletFactory implements DropletFactoryInterface
{
    /**
     * Method to retrieve the proper type of Droplet based on parameter
     *
     * @param Array $dropletInfo 
     * @return \Billow\Droplets\DropletInterface
     */
    public function getDroplet(Array $dropletInfo)
    {
        if ( !isset($dropletInfo['image']['distribution'])) {
            throw new InvalidArgumentException('Image information not found in droplet info');
        }

        $image = $dropletInfo['image']['distribution'];

        switch (strtolower($image)) {
            case 'ubuntu':
                return new Ubuntu($dropletInfo);
                break;
            case 'fedora':
                return new Fedora($dropletInfo);
                break;
            case 'debian':
                return new Debian($dropletInfo);
                break;
            case 'centos':
                return new CentOS($dropletInfo);
                break;
            case 'coreos':
                return new CoreOS($dropletInfo);
                break;
            case 'freebsd':
                return new FreeBSD($dropletInfo);
                break;
        }

        throw new RuntimeException('There is no droplet matching the image provided in the droplet info');
    }
}
