<?php
namespace marcelbonnet\Slim\Auth\Route;

use Slim\Router;

/**
 * Approach to add authorizable facilities to Slim\Route
 * @author marcelbonnet
 * @see https://github.com/slimphp/Slim/issues/1686
 * @see https://github.com/slimphp/Slim/pull/1906
 *
 */
class AuthorizableRouter extends Router {
	
	/**
	 * \Zend\Permissions\Acl\Acl shared with AuthorizableRoute
	 *
	 * @var \Zend\Permissions\Acl\Acl
	 */
	protected $acl;
	
	/**
	 * Create new router
	 * 
	 * @param RouteParser $parser
	 * @param AclInterface $acl
	 */
	public function __construct(RouteParser $parser = null, &$acl=null)
	{
		$this->acl = &$acl;
		parent::__construct($parser);
	}

	/**
	 * @return \Zend\Permissions\Acl\Acl
	 */
	public function getAcl() {
		return $this->acl;
	}
	
	/**
	 *
	 * {@inheritDoc}
	 * @see \Slim\Router::map()
	 */
	public function map($methods, $pattern, $handler)
	{
		if (!is_string($pattern)) {
			throw new InvalidArgumentException('Route pattern must be a string');
		}
	
		// Prepend parent group pattern(s)
		if ($this->routeGroups) {
			$pattern = $this->processGroups() . $pattern;
		}
	
		// According to RFC methods are defined in uppercase (See RFC 7231)
		$methods = array_map("strtoupper", $methods);
	
		// Add route
		$route = new AuthorizableRoute($methods, $pattern, $handler, $this->routeGroups, $this->routeCounter, $this->acl);
		$this->routes[$route->getIdentifier()] = $route;
		$this->routeCounter++;
		return $route;
	}
}