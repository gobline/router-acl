<?php

use Mendo\Http\Request\StringHttpRequest;
use Mendo\Router\RequestMatcher;
use Mendo\Router\Acl\RequestAuthorizer;
use Mendo\Router\RouterCollection;
use Mendo\Router\LiteralRouter;
use Mendo\Auth\CurrentUser;
use Mendo\Acl\Acl;

class RequestAuthorizerTest extends PHPUnit_Framework_TestCase
{
    private $requestMatcher;
    private $auth;
    private $acl;

    public function setup()
    {
        $routerCollection = new RouterCollection();
        $routerCollection
            ->add(new LiteralRouter('edit-profile', '/profile/edit',
                [
                    '_role' => 'member',
                ]
            ));
        $this->requestMatcher = new RequestMatcher($routerCollection);

        $this->acl = new Acl();
        $this->acl->addRole('guest')
            ->addRole('member', 'guest')
            ->addRole('admin', 'member');

        $this->auth = new CurrentUser();

        $this->requestMatcher = new RequestAuthorizer($this->requestMatcher, $this->acl, $this->auth);
    }

    public function testRequestAuthorizerAuthorizedRole()
    {
        $this->auth->setRole('member');

        $routeData = $this->requestMatcher->match(new StringHttpRequest('http://example.com/profile/edit'));

        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame('edit-profile', $routeData->getRouteName());
        $this->assertFalse($routeData->hasParam('_role'));
    }

    public function testRequestAuthorizerAuthorizedInheritedRole()
    {
        $this->auth->setRole('admin');

        $routeData = $this->requestMatcher->match(new StringHttpRequest('http://example.com/profile/edit'));

        $this->assertInstanceOf('Mendo\Router\RouteData', $routeData);
        $this->assertSame('edit-profile', $routeData->getRouteName());
        $this->assertFalse($routeData->hasParam('_role'));
    }

    public function testRequestAuthorizerUnauthorizedRole()
    {
        $this->setExpectedException('Mendo\Router\Acl\Exception\NotAuthorizedException');

        $this->auth->setRole('guest');

        $routeData = $this->requestMatcher->match(new StringHttpRequest('http://example.com/profile/edit'));
    }

    public function testRequestAuthorizerUnknownRole()
    {
        $this->setExpectedException('Mendo\Router\Acl\Exception\NotAuthorizedException');

        $this->auth->setRole('foo');

        $routeData = $this->requestMatcher->match(new StringHttpRequest('http://example.com/profile/edit'));
    }
}
