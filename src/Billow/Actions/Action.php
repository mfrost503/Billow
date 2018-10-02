<?php
namespace Billow\Actions;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Stream;
use RuntimeException;

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
     * HTTP Method
     *
     * @const METHOD
     */
    const METHOD = 'POST';

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
     * @return \GuzzleHttp\Psr7\Request
     */
    public function getRequest(Array $headers = [], $body = '')
    {
        if ($this->id === null) {
            throw new RuntimeException('You must provide the Droplet ID you want to perform an action on');
        }

        if (method_exists($this, 'getBody') && $body === '') {
            $body = $this->getBody();
        }

        if ($body === '' || json_decode($body, true) === []) {
            throw new RuntimeException('Body cannot be empty');
        }

        $endpoint = str_replace('[:id:]', $this->id, self::ENDPOINT);

        return new Request(
            static::METHOD,
            $endpoint,
            $headers,
            $body
        );
    }
}
