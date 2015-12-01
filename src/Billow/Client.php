<?php
namespace Billow;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Exception;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow 
 * @license http://opensource.org/licenses/MIT MIT
 * @method get extending the GuzzleHttp\Client::get method
 * @method post extending the GuzzleHttp\Client::post method
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
     * @return string
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
     * @return string
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
     * @param mixed $request
     * @return \GuzzleHttp\Message\Response
     * @throws \GuzzleHttp\Exception\RequestException
     * @throws \Exception
     */
    public function send($request)
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
    public function setHttpClient(\GuzzleHttp\Client $client)
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
