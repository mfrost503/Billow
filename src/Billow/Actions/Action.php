<?php
namespace Billow\Actions;
use GuzzleHttp\Message\Request;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Actions
 * @license http://opensource.org/licenses/MIT MIT
 */
class Action implements ActionInterface
{
    /**
     * Endpoint for the action
     *
     * @const ENDPOINT
     */
    const ENDPOINT = 'https://api.digitalocean.com/v2/droplets/[:id:]/actions';

    /**
     * ID of the droplet to perform the action on
     *
     * @var mixed $id
     */
    protected $id;

    /**
     * Set the droplet id
     *
     * @param mixed $id
     */
    public function setId($id)
    {
       $this->id = $id; 
    }

    /**
     * Method to return the action request object
     *
     * @param Array $headers
     * @param string $body
     * @return \GuzzleHttp\Message\Request
     */
    public function getRequest(Array $headers, $body)
    {
        $endpoint = str_replace('[:id"]', $this->id, self::ENDPOINT);
        return new Request(
            static::METHOD,
            $endpoint,
            $headers,
            $body
        );
    }
}
