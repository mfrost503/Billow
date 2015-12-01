<?php
namespace Billow;
use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\RequestInterface;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow 
 * @license http://opensource.org/licenses/MIT MIT
 * @method get extending the GuzzleHttp\Client::get method
 * @method post extending the GuzzleHttp\Client::post method
 * @method send extending the GuzzleHttp\Client::send method
 * @method getHttpClient return or create an HTTP Client to send requests
 * @method setHttpClient set the HTTP Client with an instance of ClientInterface
 * @const BASEURL baseurl for the DO API
 */
class Client implements ClientInterface
{
    /**
     * Constant to represent the base string of the API calls
     *
     * @const string BASEURL
     */
    const BASEURL = 'https://api.digitalocean.com/v2/';

    /**
     * An HTTP Client to perform the HTTP Requests
     *
     * @var \GuzzleHttp\Client
     */
    private $httpClient;
    
    /**
     * Wrapper for the GuzzleHttp\Client::get() method
     *
     * @param $url string
     * @param Array $options
     * @return \GuzzleHttp\Message\ResponseInterface 
     * @throws \GuzzleHttp\Exception\RequestException
     * @throws \Exception
     */
    public function get($url = null, Array $options = [])
    {
        $this->getHttpClient();
        try {
            $response = $this->httpClient->get($url, $options);
            return $response;
        } catch (RequestException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Wrapper for the GuzzleHttp\Client::post() method
     *
     * @param $url string
     * @param Array $options
     * @return \GuzzleHttp\Message\ResponseInterface 
     * @throws \GuzzleHttp\Exception\RequestException
     * @throws \Exception
     */
    public function post($url = null, Array $options = [])
    {
        $this->getHttpClient();
        try {
            $response = $this->httpClient->post($url, $options);
            return $response;
        } catch (RequestException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to send a request object via HTTP and retrieve a response
     *
     * @param \GuzzleHttp\Message\RequestInterface $request
     * @return \GuzzleHttp\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\RequestException
     * @throws \Exception
     */
    public function send(RequestInterface $request)
    {
        $this->getHttpClient();
        try {
            $response = $this->httpClient->send($request);
            return $response;
        } catch (RequestException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Method to set the HttpClient
     *
     * @param \GuzzleHttp\Client
     */
    public function setHttpClient(Client $client)
    {
        $this->httpClient = $client;
    }

    /**
     * Method to retrieve or instantiate an Http Client
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        if (!isset($this->httpClient)) {
            $this->httpClient = new HttpClient(['base_url' => self::BASEURL]);
        }
        return $this->httpClient;
    }
} 
