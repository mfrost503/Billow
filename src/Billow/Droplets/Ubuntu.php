<?php
namespace Billow\Droplets;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Droplets
 * @license http://opensource.org/licenses/MIT MIT
 */
class Ubuntu extends Droplet implements DropletInterface
{
    /**
     * Constant representing the box image to use
     *
     * @const IMAGE
     */
    const IMAGE = 'ubuntu-14-04-x64';

    /**
     * Constructor - overridden to ensure that the image is set correctly
     *
     * @param Array boxData
     */
    public function __construct(Array $boxData)
    {
        $this->setImage(self::IMAGE);
        parent::__construct($boxData);
    }
}
