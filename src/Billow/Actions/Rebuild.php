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
     * @param Array $values
     * @throws \InvalidArgumentException
     */
    public function __construct(Array $values)
    {
        if (!isset($values['image'])) {
            throw new InvalidArgumentException('Required value "image" is not present');
        }

        if (!is_numeric($values['image']) && !is_string($values['image'])) {
            throw new InvalidArgumentException("Image argument must be a slug or ID");
        }
        $this->image = $values['image'];
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

