<?php
namespace marcelbonnet\Slim\Auth\Route;

use Slim\Route;

/**
 * Approach to add authorizable facilities to Slim\Route
 * @author marcelbonnet
 * @see https://github.com/slimphp/Slim/issues/1686
 * @see https://github.com/slimphp/Slim/pull/1906
 *
 */
class AuthorizableRoute extends Route {
	
	/**
	 * AclInterface.
	 *
	 * @var AclInterface
	 */
	protected $acl;
	
	/**
	 * Create new route
     *
     * @param string[]     $methods The route HTTP methods
     * @param string       $pattern The route pattern
     * @param callable     $callable The route callable
     * @param int          $identifier The route identifier
     * @param RouteGroup[] $groups The parent route groups
	 * @param AclInterface $acl
	 */
	public function __construct($methods, $pattern, $callable, $groups, $identifier, &$acl=null)
	{
		$this->acl = $acl;
		parent::__construct($methods, $pattern, $callable, $groups, $identifier);
	}
	
	/**
	 *
	 * @param string|array $roles
	 */
	public function allow($roles){
		//TODO
		//conflita com new Acl() se houver resources adicionados e tals, ou terá uma prioridade ?
	}
	
	/**
	 * @return AclInterface
	 */
	public function getAcl() {
		return $this->acl;
	}
	
}