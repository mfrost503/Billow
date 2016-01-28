<?php
namespace Billow\Actions;
use InvalidArgumentException;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Actions
 * @license http://opensource.org/licenses/MIT MIT
 */
class Resize extends Action
{
    /**
     * Action parameter
     *
     * @const ACTION
     */
    const ACTION = 'resize';

    /**
     * Action HTTP Method
     *
     * @const method
     */
    const METHOD = 'POST';

    /**
     * Size slug
     *
     * @var string size
     */
    protected $size;

    /**
     * Boolean to determine whether to increase disk size
     *
     * @var bool disk
     */
    protected $disk;

    /**
     * Constructor for the Resize Action
     *
     * @param Array values
     */
    public function __construct(Array $values)
    {
        if (!isset($values['size'])) {
            throw new InvalidArgumentException('Required value "size" is not present');
        }

        if (!is_string($values['size'])) {
            throw new InvalidArgumentException('The size parameter must be a string (slug)');
        }

        if (isset($values['disk']) && !is_bool($values['disk'])) {
            throw new InvalidArgumentException('The disk parameter must be a boolean');
        }

        $this->size = $values['size'];
        $this->disk = (isset($values['disk'])) ? $values['disk'] : true;
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
            'disk' => $this->disk,
            'size' => $this->size
        ]);
    }
}

