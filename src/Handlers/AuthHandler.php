<?php
/**
 * Slim Auth.
 *
 * @link      http://github.com/marcelbonnet/slim-auth
 *
 * @copyright Copyright (c) 2013-2016 Jeremy Kendall (http://about.me/jeremykendall) with changes: (c) 2016 Marcel Bonnet (http://github.com/marcelbonnet)
 * @license   MIT
 */
namespace marcelbonnet\Slim\Auth\Handlers;

use Psr\Http\Message\ResponseInterface;

/**
 * Auth Handler interface.
 */
interface AuthHandler
{
    /**
     * Perform some action if user is not authenticated.
     *
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     *
     * @throws \marcelbonnet\Slim\Auth\Exception\HttpException
     */
    public function notAuthenticated(ResponseInterface $response);

    /**
     * Perform some action if user is not authorized.
     *
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     *
     * @throws \marcelbonnet\Slim\Auth\Exception\HttpException
     */
    public function notAuthorized(ResponseInterface $response);
}
