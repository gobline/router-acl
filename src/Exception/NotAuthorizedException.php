<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mendo\Router\Acl\Exception;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class NotAuthorizedException extends \RuntimeException 
{
    public function __construct($resource)
    {
        parent::__construct('Access denied to "'.$resource.'"', 403);
    }
}
