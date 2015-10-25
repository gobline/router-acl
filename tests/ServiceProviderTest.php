<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Mendo\Router\Acl\Provider\Pimple\RequestAuthorizerServiceProvider;
use Mendo\Router\Provider\Pimple\RouterServiceProvider;
use Mendo\Auth\Provider\Pimple\AuthServiceProvider;
use Mendo\Acl\Provider\Pimple\AclServiceProvider;
use Pimple\Container;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class ServiceProviderTest extends PHPUnit_Framework_TestCase
{
    public function testServiceProvider()
    {
        $container = new Container();
        $container->register(new AclServiceProvider('acl'));
        $container->register(new AuthServiceProvider('auth'));
        $container['auth.session'] = false;
        $container->register(new RouterServiceProvider('router'));
        $container->register(new RequestAuthorizerServiceProvider());
        $container['router.acl.requestAuthorizer.decorated'] = 'router.requestMatcher';
        $this->assertInstanceOf('Mendo\Router\Acl\RequestAuthorizer', $container['router.acl.requestAuthorizer']);
    }
}
