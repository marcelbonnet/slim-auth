<?php

/**
 * Slim Auth.
 *
 * @link      http://github.com/jeremykendall/slim-auth Canonical source repo
 *
 * @copyright Copyright (c) 2013-2016 Jeremy Kendall (http://about.me/jeremykendall)
 * @license   http://github.com/jeremykendall/slim-auth/blob/master/LICENSE MIT
 */
namespace marcelbonnet\Slim\Auth\Handlers;

use marcelbonnet\Slim\Auth\Exception\HttpForbiddenException;
use marcelbonnet\Slim\Auth\Exception\HttpUnauthorizedException;
use Psr\Http\Message\ResponseInterface;

/**
 * Throws exceptions based on intended HTTP response.
 */
final class ThrowHttpExceptionHandler implements AuthHandler
{
    /**
     * Throws HttpUnauthorizedException to be handled elsewhere.
     *
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     *
     * @throws \marcelbonnet\Slim\Auth\Exception\HttpUnauthorizedException
     */
    public function notAuthenticated(ResponseInterface $response)
    {
        throw new HttpUnauthorizedException();
    }

    /**
     * Throws HttpForbiddenException to be handled elsewhere.
     *
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     *
     * @throws \marcelbonnet\Slim\Auth\Exception\HttpForbiddenException
     */
    public function notAuthorized(ResponseInterface $response)
    {
        throw new HttpForbiddenException();
    }
}
