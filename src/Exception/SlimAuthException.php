<?php
/**
 * Slim Auth.
 *
 * @link      http://github.com/marcelbonnet/slim-auth
 *
 * @copyright Copyright (c) 2016 Marcel Bonnet (http://github.com/marcelbonnet)
 * @license   MIT
 */
namespace marcelbonnet\Slim\Auth\Exception;

use Exception;

/**
 * Base exception class for all Slim Auth Exceptions
 *
 * @author marcelbonnet
 * @since 0.0.2
 */
class SlimAuthException extends Exception
{
    /**
     * @return SlimAuthException
     */
    public static function fileDoesNotExist($file)
    {
        return new self("$file does not exist in file system.");
    }
    
    /**
     * @return SlimAuthException
     */
    public static function configFileIsNotSet()
    {
        return new self("A config file must be set for LDAP options.");
    }


    
}
