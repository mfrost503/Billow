<?php
namespace Billow;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\Response;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @license http://opensource.org/licenses/MIT MIT
 * @method setClient set the HTTP Client
 * @method getClient retrieve or create an HTTP Client
 * @method create create a droplet 
 */
class Droplet
{
    /**
     * Client to make http requests
     *
     * @var Client
     */
    private $client;

    /**
     * Method to set client
     *
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Method to retrieve set client or generate
     * a new client if one is not set
     *
     * @return Client
     */
    public function getClient()
    {
        if ($this->client === null) {
            $this->client = new Client();
        }
        return $this->client;
    }

    /**
     * Method to create a new Digital Ocean Droplet
     *
     * @param \Billow\Droplets\Droplet $droplet
     * @param Array $headers
     * @return \GuzzleHttp\Message\Response
     */
    public function create(Droplets\Droplet $droplet, Array $headers =[])
    {
        if (!isset($headers['Content-type']) && !isset($headers['Content-Type'])) {
            $headers['Content-type'] = 'application/json';
        }

        $dropletJSON = $droplet->toJSON();
        $params = ['headers' => $headers, 'body' => $dropletJSON];

        $client = $this->getClient();
        try {
            $response = $client->post('droplets', $params);
            return $response;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse();
            }

            return new Response(
                0
            );
        }
    }
}
