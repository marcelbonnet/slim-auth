<?php
namespace marcelbonnet\Slim\Auth\Events;

/**
 * Slim Authorization Events
 * 
 * May be used to log when an attempt to to
 * authorize a route fails, ... etc
 * 
 * @author marcelbonnet
 * 
 * @link      http://github.com/marcelbonnet/slim-auth
 *
 * @copyright Copyright (c) 2016 Marcel Bonnet (http://github.com/marcelbonnet)
 * @license   MIT
 *
 */
interface SlimAuthorizationEventInterface {
	
	
	/**
	 * Fired after a failed authorization
	 */
	public function onFail($identity=null, $message=null);
}