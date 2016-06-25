<?php
/**
 * Slim Auth.
 *
 * @link      http://github.com/marcelbonnet/slim-auth
 *
 * @copyright Copyright (c) 2013-2016 Jeremy Kendall (http://about.me/jeremykendall) with changes: (c) 2016 Marcel Bonnet (http://github.com/marcelbonnet)
 * @license   MIT
 */
namespace marcelbonnet\Slim\Auth;

/**
 * Interface for identity classes.
 */
interface IdentityInterface
{
    /**
     * Gets user's role.
     *
     * @return string User's role in application
     */
    public function getRole();
}
