<?php
namespace Services\DigitalOcean;
use GuzzleHttp\Exception\RequestException;

/**
 * @author Matt Frost<mattf@budgetdumpster.com>
 * @package Services
 * @subpackage DigitalOcean
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

    public function create(Droplets\Droplet $droplet, Array $headers =[])
    {
        if (!isset($headers['Content-type'])) {
            $headers['Content-type'] = 'application/json';
        }
        $dropletJSON = $droplet->toJSON();
        $params = ['headers' => $headers, 'body' => $dropletJSON];

        $client = $this->getClient();
        try {
            $response = $client->post('droplets', $params);
            return $response;
        } catch (RequestException $e) {
            exit('exception caught');
        }
    }
}
