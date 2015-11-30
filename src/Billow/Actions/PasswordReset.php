<?php
namespace Billow\Actions;

/**
 * @author Matt Frost<mfrost.design@gmail.com>
 * @package Billow
 * @subpackage Actions
 * @license http://opensource.org/licenses/MIT MIT
 */
class PasswordReset extends Action
{
    /**
     * Action parameter
     *
     * @const ACTION
     */
    const ACTION = 'password_reset';

    /**
     * Action HTTP Method
     *
     * @const method
     */
    const METHOD = 'POST';

    /**
     * Return the body of the request
     *
     * @return string json representation of the body
     */
    public function getBody()
    {
        return json_encode([
            'type' => self::ACTION
        ]);
    }
}

