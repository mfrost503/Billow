<?php
namespace Billow;
use GuzzleHttp\Message\RequestInterface;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @license http://opensource.org/licenses/MIT MIT
 */
interface ClientInterface
{

    /**
     * Get method that will be used to make a get HTTP Request
     *
     * @param string url
     * @param Array options
     */
    public function get($url = null, Array $options = []);

    /**
     * Method that must be implement to make an HTTP Post request
     *
     * @param string url
     * @param Array options
     */
    public function post($url = null, Array $options = []);

    /**
     * Method that takes in a request parameter and sends it as an
     * HTTP Request
     *
     * @param \GuzzleHttp\Message\RequestInterface request
     */
    public function send(RequestInterface $request);
}
