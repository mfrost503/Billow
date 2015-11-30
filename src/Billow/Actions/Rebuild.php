<?php
namespace Billow\Actions;
use InvalidArgumentException;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Actions
 * @license http://opensource.org/licenses/MIT MIT
 */
class Rebuild extends Action
{
    /**
     * Action parameter
     *
     * @const ACTION
     */
    const ACTION = 'rebuild';

    /**
     * Action HTTP Method
     *
     * @const method
     */
    const METHOD = 'POST';

    /**
     * Image slug or ID
     *
     * @var mixed image
     */
    protected $image;

    /**
     * Constructor for rebuild action
     *
     * @param mixed image
     * @throws \InvalidArgumentException
     */
    public function __construct($image)
    {
        if (!is_numeric($image) && !is_string($image)) {
            throw new InvalidArgumentException("Image argument must be a slug or ID");
        }
        $this->image = $image;
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
            'image' => $this->image
        ]);
    }
}

