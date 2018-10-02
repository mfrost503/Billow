<?php
namespace Billow\Actions;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Actions
 * @license http://opensource.org/licenses/MIT MIT
 */
interface ActionInterface
{

    /**
     * Method to retrieve the request body for the specified action
     *
     * @param Array $headers
     * @param string $body
     * @return \GuzzleHttp\Psr7\Request 
     */
    public function getRequest(Array $headers, $body);

    /**
     * Method to set the id for the request
     *
     * @param mixed $id
     */
    public function setId($id);
}
    
