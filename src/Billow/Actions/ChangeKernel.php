<?php
namespace Billow\Actions;
use InvalidArgumentException;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Actions
 * @license http://opensource.org/licenses/MIT MIT
 */
class ChangeKernel extends Action
{
    /**
     * Action parameter
     *
     * @const ACTION
     */
    const ACTION = 'change_kernel';

    /**
     * Action HTTP Method
     *
     * @const method
     */
    const METHOD = 'POST';

    /**
     * Unique number used to identify a specific kernel
     *
     * @var int kernel
     */
    protected $kernel;

    /**
     * Constructor for change kernel action
     *
     * @param int kernel
     * @throws \InvalidArgumentException
     */
    public function __construct($kernel)
    {
        if (!is_int($kernel)) {
            throw new InvalidArgumentException('Kernel argument must be an integer');
        }
        $this->kernel = $kernel;
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
            'kernel' => $this->kernel
        ]);
    }
}

