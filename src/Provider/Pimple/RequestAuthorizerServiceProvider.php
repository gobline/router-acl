<?php

/*
 * Mendo Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mendo\Router\Acl\Provider\Pimple;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Mendo\Router\Acl\RequestAuthorizer;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class RequestAuthorizerServiceProvider implements ServiceProviderInterface
{
    private $reference;

    public function __construct($reference = 'router.acl.requestAuthorizer')
    {
        $this->reference = $reference;
    }

    public function register(Container $container)
    {
        $container[$this->reference.'.decorated'] = 'router.requestMatcher';
        $container[$this->reference.'.auth'] = 'auth';
        $container[$this->reference.'.acl'] = 'acl';

        $container[$this->reference] = function ($c) {
            return new RequestAuthorizer(
                $c[$c[$this->reference.'.decorated']],
                $c[$c[$this->reference.'.acl']],
                $c[$c[$this->reference.'.auth']]);
        };
    }
}
