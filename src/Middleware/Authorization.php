<?php
/**
 * Slim Auth.
 *
 * @link      http://github.com/marcelbonnet/slim-auth
 *
 * @copyright Copyright (c) 2013-2016 Jeremy Kendall (http://about.me/jeremykendall) with changes: (c) 2016 Marcel Bonnet (http://github.com/marcelbonnet)
 * @license   MIT
 */
namespace marcelbonnet\Slim\Auth\Middleware;

use marcelbonnet\Slim\Auth\Handlers\AuthHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\Permissions\Acl\AclInterface;
use marcelbonnet\Slim\Auth\Route\AuthorizableRoute;

/**
 * Authorization middleware: Checks user's authorization to access the
 * requested URI.
 */
final class Authorization
{
    /**
     * Authentication service.
     *
     * @var AuthenticationServiceInterface
     */
    private $auth;

    /**
     * AclInterface.
     *
     * @var AclInterface
     */
    private $acl;

    /**
     * AuthHandler interface.
     *
     * @var AuthHandler
     */
    private $handler;

    /**
     * Public constructor.
     *
     * @param AuthenticationServiceInterface $auth    Authentication service
     * @param AclInterface                   $acl     Zend AclInterface
     * @param AuthHandler                    $handler AuthHandler interface
     */
    public function __construct(
        AuthenticationServiceInterface $auth,
        AclInterface &$acl,
        AuthHandler $handler
    ) {
        $this->auth = $auth;
        $this->acl = &$acl;
        $this->handler = $handler;
    }

    /**
     * Determines whether or not user has access to requested resource.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $route = $request->getAttribute('route', null);

        if ($route === null) {
            // User likely accessing a nonexistent route. Calling next middleware.
            return $next($request, $response);
        }

        $role = $this->getRole($this->auth->getIdentity());
        $resource = $route->getPattern();

        /*
         * THIS BUG HAPPENED WHEN ROUTE DID NOT SET ->allow([roles])
         * Hope fix problems when an optional / maybe followed by arguments
         * Route::group('/venues', function (){
				Route::get('/', ...
				Route::get('[/{id:[0-9]+}]', ...
				
		   dont work for groups that do not have a sub route like '/'
         */
//         $resource = preg_replace("|\[\/[^\[].*\]|", "/", $route->getPattern());
//         $resource = $route->getIdentifier();
        $privilege = $request->getMethod();
//         $isAllowed = false;
        
//         if(!$this->acl && $route instanceof AuthorizableRoute){
//         	$route->getAcl()->isAllowed($role, $resource, $privilege);
//         } else {
// 	        $this->acl->isAllowed($role, $resource, $privilege);
//         }
// 		var_dump($this->acl);
        $isAllowed = $this->acl->isAllowed($role, $resource, $privilege);
        $isAuthenticated = $this->auth->hasIdentity();

        if ($isAllowed) {
            return $next($request, $response);
        }

        if ($isAuthenticated) {
            // Authenticated but unauthorized for this resource
            return $this->handler->notAuthorized($response);
        }

        // Not authenticated and must be authenticated to access this resource
        return $this->handler->notAuthenticated($response);
    }

    /**
     * Gets handler.
     *
     * @return AuthHandler
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Gets role from user's identity.
     *
     * @param mixed $identity User's identity. If null, returns role 'guest'
     *
     * @return string User's role
     */
    private function getRole($identity = null)
    {
        if (is_object($identity)) {
            return $identity->getRole();
        }

        if (is_array($identity) && isset($identity['role'])) {
            return $identity['role'];
        }

        return 'guest';
    }
    
    /**
     * Sets an AuthenticationServiceInterface
     * @param AuthenticationServiceInterface $auth
     */
    public function setAuth(AuthenticationServiceInterface $auth) {
    	$this->auth = $auth;
    }
    
    /**
     * Sets an ACL Interface
     * @param AclInterface $acl
     */
    public function setAcl(AclInterface $acl) {
    	$this->acl = $acl;
    }
    
    /**
     * Sets Handlers
     * @param AuthHandler $handler
     */
    public function setHandler(AuthHandler $handler) {
    	$this->handler = $handler;
    }
}

