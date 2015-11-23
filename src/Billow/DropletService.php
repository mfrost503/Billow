<?php
namespace Billow;
use Billow\Droplets\DropletInterface;
use Billow\Droplets\DropletFactory;
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
class DropletService
{
    /**
     * Droplet Factory
     *
     * @var \Billow\Droplets\DropletFactory
     */
    private $factory;

    /**
     * Client to make http requests
     *
     * @var Client
     */
    private $client;

    /**
     * Method to set factory
     *
     * @param \Billow\Droplets\DropletFactory $factory
     */
    public function setFactory(DropletFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Method to retrieve or instantiate a Droplet Factory
     *
     * @return \Billow\Droplets\DropletFactory
     */
    public function getFactory()
    {
        if (!is_null($this->factory)) {
            return $this->factory;
        }
        return new DropletFactory();
    }

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
        $headers = $this->prepareHeaders($headers);

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

            return new Response(0);
        }
    }

    /**
     * Method to retrieve a droplet by id
     *
     * @param int $dropletId
     * @param Array $headers
     * @return \Billow\Droplets\Droplet
     */
    public function retrieve($dropletId, Array $headers = [])
    {
        $headers = $this->prepareHeaders($headers);
        $params = ['headers' => $headers];
        $client = $this->getClient();
        try {
            $response = $client->get('droplets/' . $dropletId, $params);
            $factory = $this->getFactory();
            return $factory->getDroplet(json_decode($response->getBody(), true));
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                return $e->getResponse();
            }

            return new Response(0);
        }
    }

    /**
     * Method to perform an action on a Digital Ocean Droplet
     *
     * @param int $droplet_id
     * @param \Billow\Actions\ActionInterface $action
     * @param Array $headers
     * @return \GuzzleHttp\Message\Response
     */
    public function performAction(Droplet $droplet, ActionInterface $action, Array $headers = [])
    {
        $dropletValues = $droplet->toArray();   
        $id = $dropletValues['id'];
        $action->setId($id);
        $request = $action->getRequest($headers, $action->getBody());
        $client = $this->getClient();
        $response = $client->send($request);
        return $response;       
    }

    /**
     * Method to set the default Content-type if one is not sent
     *
     * @param Array Headers
     * @return Array
     */ 
    private function prepareHeaders(array $headers)
    {
        if (!isset($headers['Content-type']) && !isset($headers['Content-Type'])) {
            $headers['Content-type'] = 'application/json';
        } 
        return $headers;
    }
}
