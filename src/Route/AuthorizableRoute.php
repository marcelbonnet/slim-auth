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
	 * Zend Acl
	 *
	 * @var \Zend\Permissions\Acl\Acl
	 */
	protected $acl;
	
	/**
	 * Create new route
     *
     * @param string[]     				$methods The route HTTP methods
     * @param string       				$pattern The route pattern
     * @param callable     				$callable The route callable
     * @param int          				$identifier The route identifier
     * @param RouteGroup[] 				$groups The parent route groups
	 * @param \Zend\Permissions\Acl\Acl 	$acl
	 */
	public function __construct($methods, $pattern, $callable, $groups, $identifier, &$acl=null)
	{
		$this->acl = &$acl;
		parent::__construct($methods, $pattern, $callable, $groups, $identifier);
	}
	
	/**
	 *
	 * @param string|array $roles
	 */
	public function allow($roles){
		/*
		 * TODO: $this->getPattern() resolves the wrong uri/route, and return the route's group inner uri.
		 * could use Twig's:
		public function pathFor($name, $data = [], $queryParams = [], $appName = 'default')
	    {
	        return $this->router->pathFor($name, $data, $queryParams);
	    }
		 */
// 		$uri		= $this->container['request']->getUri();
// 		$resource 	= $uri->getBasePath() ."/". $uri->getPath();
// 		$resource 	= preg_match("|^\/$|" , $uri->getPath())? $uri->getPath() : "/". $uri->getPath();
// 		$resource	= $this->getIdentifier();
		$resource	= $this->getPattern();
// 		var_dump($this->getPattern());
		if(! $this->getAcl()->hasResource($resource) ){
			$this->getAcl()->addResource($resource);
		}
		$this->getAcl()->allow($roles, $resource, $this->getMethods());
		return $this;
	}
	
	/**
	 * @return \Zend\Permissions\Acl\Acl
	 */
	public function getAcl() {
		return $this->acl;
	}
	
}