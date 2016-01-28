<?php
namespace Billow\Actions;
use InvalidArgumentException;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Actions
 * @license http://opensource.org/licenses/MIT MIT
 */
class Restore extends Action
{
    /**
     * Action parameter
     *
     * @const ACTION
     */
    const ACTION = 'enable_backups';

    /**
     * Action HTTP Method
     *
     * @const method
     */
    const METHOD = 'POST';

    /**
     * Image ID
     *
     * @var int $image
     */
    protected $image;

    /**
     * Constructor for Restore Action
     *
     * @param Array values 
     */
    public function __construct(Array $values)
    {
        if (!isset($values['image'])) {
            throw new InvalidArgumentException('Require value "image" is not present');
        }

        if (!is_numeric($values['image']) && !is_string($values['image'])) {
            throw new InvalidArgumentException('Image parameter must be an ID or a string');
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
