<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mendo\Router\Acl;

use Mendo\Acl\AclInterface;
use Mendo\Auth\CurrentUserInterface;
use Mendo\Http\Request\HttpRequestInterface;
use Mendo\Router\RequestMatcherInterface;
use Mendo\Router\RouteData;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class RequestAuthorizer implements RequestMatcherInterface
{
    private $requestMatcher;
    private $currentUser;
    private $acl;

    public function __construct(
        RequestMatcherInterface $requestMatcher,
        AclInterface $acl,
        CurrentUserInterface $currentUser
    ) {
        $this->requestMatcher = $requestMatcher;
        $this->currentUser = $currentUser;
        $this->acl = $acl;
    }

    public function match(HttpRequestInterface $httpRequest)
    {
        $routeData = $this->requestMatcher->match($httpRequest);

        $params = $routeData->getParams();
        if (isset($params['_role'])) {
            $role = $params['_role'];
            unset($params['_role']);

            if (!$this->isAuthorized($role)) {
                throw new Exception\NotAuthorizedException($routeData->getRouteName());
            }
        }

        return new RouteData($routeData->getRouteName(), $params);
    }

    public function isAuthorized($role)
    {
        if (!$this->acl->hasRole($role)) {
            return false;
        }

        $role = $this->acl->getRole($role);

        $userRoleName = $this->currentUser->getRole();
        if (!$this->acl->hasRole($userRoleName)) {
            return false;
        }

        $userRole = $this->acl->getRole($userRoleName);

        if (!$userRole->equals($role) && !$userRole->inherits($role)) {
            return false;
        }

        return true;
    }
}
