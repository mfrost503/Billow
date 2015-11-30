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
     * @param bool disk
     * @param string size
     */
    public function __construct($size, $disk = true)
    {
        if (!is_string($size)) {
            throw new InvalidArgumentException('The size parameter must be a string (slug)');
        }

        if (!is_bool($disk)) {
            throw new InvalidArgumentException('The disk parameter must be a boolean');
        }

        $this->size = $size;
        $this->disk = $disk;
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

