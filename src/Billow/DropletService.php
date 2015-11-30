<?php
namespace Billow;
use Billow\Droplets\DropletFactory;
use Billow\Actions\ActionInterface;
use Billow\Droplets\DropletInterface;
use Billow\Exceptions\ProvisionException;
use Billow\Exceptions\DropletException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\Response;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @license http://opensource.org/licenses/MIT MIT
 * @method setClient set the HTTP Client
 * @method getClient retrieve or create an HTTP Client
 * @method create create a droplet 
 * @method retrieve retrieve a droplet by id
 * @method performAction perform a predefined action on a droplet
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
     * @param Array $dropletRequest
     * @param Array $headers
     * @return \GuzzleHttp\Message\Response
     * @throws \Billow\Exceptions\ProvisionException
     */
    public function create(Array $dropletRequest, Array $headers =[])
    {
        $headers = $this->prepareHeaders($headers);

        $dropletJSON = json_encode($dropletRequest);
        $params = ['headers' => $headers, 'body' => $dropletJSON];

        $client = $this->getClient();
        try {
            $response = $client->post('droplets', $params);
            return $response;
        } catch (RequestException $e) {
            $message = 'Failed to provision new droplet';
            $code = 0;
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $message = $response->getBody();
                $code = $response->getStatusCode();
            }
            throw new ProvisionException($message, $code, $e);
        }
    }

    /**
     * Method to retrieve a droplet by id
     *
     * @param int $dropletId
     * @param Array $headers
     * @return \Billow\Droplets\Droplet
     * @throws \Billow\Exceptions\DropletException
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
            $message = 'Retrieval of droplet failed';
            $code = 0;
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $message = $response->getBody();
                $code = $response->getStatusCode();
            }
            throw new DropletException($message, $code, $e);
        }
    }

    /**
     * Method to perform an action on a Digital Ocean Droplet
     *
     * @param \Billow\Droplets\DropletInterface
     * @param \Billow\Actions\ActionInterface $action
     * @param Array $headers
     * @return \GuzzleHttp\Message\Response
     */
    public function performAction(DropletInterface $droplet, ActionInterface $action, Array $headers = [])
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
