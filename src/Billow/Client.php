<?php
namespace Billow;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow 
 * @license http://opensource.org/licenses/MIT MIT
 * @method get extending the GuzzleHttp\Client::get method
 * @method post extending the GuzzleHttp\Client::post method
 * @const BASEURL baseurl for the DO API
 */
class Client extends HttpClient
{
    /**
     * Constant to represent the base string of the API calls
     *
     * @const string BASEURL
     */
    const BASEURL = 'https://api.digitalocean.com/v2/';

    /**
     * Constructor to configure the Guzzle Client
     */
    public function __construct()
    {
        parent::__construct(['base_url' => self::BASEURL]);
    }
    
    /**
     * Wrapper for the GuzzleHttp\Client::get() method
     *
     * @param $url string
     * @param Array $options
     * @return string
     */
    public function get($url = null, $options = [])
    {
        try {
            $response = parent::get($url, $options);
             return $response;
        } catch (RequestException $e) {
            $response = null;
            if ($e->hasResponse()) {
                $response = $e->getResponse();
            }
            return [
                'message' => $e->getMessage(),
                'status_code' => $e->getCode(),
                'request' => $e->getRequest(),
                'response' => $response
            ];
        } 
    }

    /**
     * Wrapper for the GuzzleHttp\Client::post() method
     *
     * @param $url string
     * @param Array $options
     * @return string
     */
    public function post($url = NULL, array $options = [])
    {
        try {
            $response = parent::post($url, $options);
             return $response;
        } catch (RequestException $e) {
            $response = null;
            if ($e->hasResponse()) {
                $response = $e->getResponse();
            }
            return [
                'message' => $e->getMessage(),
                'status_code' => $e->getCode(),
                'request' => $e->getRequest(),
                'response' => $response
            ];
        } 
    }
} 
