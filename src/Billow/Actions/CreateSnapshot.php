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
    private $name;

    /**
     * Constructor for the create snapshot action
     *
     * @param Array values
     * @throws \InvalidArgumentException
     */
    public function __construct(Array $values)
    {
        if (!isset($values['name'])) {
            throw new InvalidArgumentException('Required value "Name" is not present');
        }

        if (!is_string($values['name'])) {
            throw new InvalidArgumentException('Name parameter must be a string');
        }

        $this->name = $values['name'];
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

