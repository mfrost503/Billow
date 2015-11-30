<?php
namespace Billow\Actions;
use InvalidArgumentException;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Actions
 * @license http://opensource.org/licenses/MIT MIT
 */
class CreateSnapshot extends Action
{
    /**
     * Action parameter
     *
     * @const ACTION
     */
    const ACTION = 'snapshot';

    /**
     * Action HTTP Method
     *
     * @const method
     */
    const METHOD = 'POST';

    /**
     * Name of the snapshot to be created
     *
     * @var string name
     */

    /**
     * Constructor for the create snapshot action
     *
     * @param string name
     * @throws \InvalidArgumentException
     */
    public function __construct($name)
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException('Name parameter must be a string');
        }

        $this->name = $name;
    }

    /**
     * Return the body of the request
     *
     * @return string json representation of the body
     */
    public function getBody()
    {
        return json_encode([
            'type' => self::ACTION,
            'name' => $this->name
        ]);
    }
}

