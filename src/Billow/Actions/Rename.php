<?php
namespace Billow\Actions;
use InvalidArgumentException;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Actions
 * @license http://opensource.org/licenses/MIT MIT
 */
class Rename extends Action
{
    /**
     * Action parameter
     *
     * @const ACTION
     */
    const ACTION = 'rename';

    /**
     * Action HTTP Method
     *
     * @const method
     */
    const METHOD = 'POST';

    /**
     * Name that the droplet will be changed to
     *
     * @param string name
     */
    protected $name;

    /**
     * Constructor for the rename action
     *
     * @param string name
     * @throws \InvalidArgumentException
     */
    public function __construct($name)
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException('Name argument must be a string');
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

