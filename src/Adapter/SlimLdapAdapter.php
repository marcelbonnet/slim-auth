<?php
/**
 * Slim Auth.
 *
 * @link      http://github.com/marcelbonnet/slim-auth
 *
 * @copyright Copyright (c) 2016 Marcel Bonnet (http://github.com/marcelbonnet)
 * @license   MIT
 */
namespace marcelbonnet\Slim\Auth\Adapter;

use Zend\Authentication\Adapter\Ldap;
use marcelbonnet\Slim\Auth\Exception\SlimAuthException;

/**
 * LDAP Adapter for Slim Framework
 * @author marcelbonnet
 * @since 0.0.2
 */
class SlimLdapAdapter extends Ldap{

	protected static $configFile = null;
	
	public function __construct(array $options=array() ,$identity=null,$credential=null)
	{ 
		parent::__construct( $options , $identity=null, $credential=null);
	}

	/**
	 * @param string $filename LDAP config file
	 * @throws \marcelbonnet\Slim\Auth\Exception\SlimAuthException
	 */
	public static function addLdapConfig($filename){
		if(!file_exists($filename)){
			throw SlimAuthException::fileDoesNotExist($filename);
		}
		
		self::$configFile = $filename;
	}
	
	public function authenticate($username=null, $password=null)
	{
		if(self::$configFile === null){
			throw SlimAuthException::configFileIsNotSet();
		}
		//TODO check what Zend Exceptions may occour, if any, and enclose in SlimAuthException messages
		$configReader = new \Zend\Config\Reader\Ini();
		$configData = $configReader->fromFile(self::$configFile);
		$config = new \Zend\Config\Config($configData, false);
		$options = $config->ldapauth->ldap->toArray();
		$this->setOptions($options);
		$this->setUsername($username);
		$this->setPassword($password);
		return parent::authenticate();
	}
}