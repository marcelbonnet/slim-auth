<?php
namespace marcelbonnet\Slim\Auth\Events;

/**
 * Slim Authentication Events
 * 
 * May be used to log when an attempt to login fails or
 * to choose another provider to challenge; to do other
 * stuff needed just after a successfull login... etc.
 * 
 * @author marcelbonnet
 * 
 * @link      http://github.com/marcelbonnet/slim-auth
 *
 * @copyright Copyright (c) 2016 Marcel Bonnet (http://github.com/marcelbonnet)
 * @license   MIT
 *
 */
interface SlimAuthEventInterface {

	/**
	 * Fired after a succesfull authentication
	 * @param string $identity the identity
	 * @param array $roles the roles
	 * @return array Any object to be injected in Zend\Authentication\Result
	 */
	public function onLogin($identity=null, array $roles=null);
	
	/**
	 * Fired after a failed authentication
	 * @param string $identity the identity
	 * @param string $message the error message returned by the provider
	 */
	public function onFail($identity=null, $message=null);
}