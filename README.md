# Router ACL Component - Mendo Framework

This component allows to add [ACL](https://github.com/mendoframework/acl) support to your routes, or in other words, to control access to protected routes. It is a [Decorator](https://en.wikipedia.org/wiki/Decorator_pattern) over a `Mendo\Router\RequestMatcherInterface` instance.

The component requires three dependencies:

* a `Mendo\Acl\AclInterface` instance for ACL support ([acl component](https://github.com/mendoframework/acl))
* a `Mendo\Auth\CurrentUserInterface` instance to get the user's role ([auth component](https://github.com/mendoframework/auth))
* a `Mendo\Router\RequestMatcherInterface` instance ([router component](https://github.com/mendoframework/router))

Routes can be protected by specifying the required user role in order to access that route. This is done simply by adding the ```_role``` route parameter to the corresponding router.

```php
$router = new Mendo\Router\LiteralRouter('edit-profile', '/profile/edit',
    [
        '_role' => 'member',
    ]
);
```

```php
$requestMatcher = new Mendo\Router\Acl\RequestAuthorizer($dic['router.requestMatcher'], $dic['acl'], $dic['auth']);
```

The example above restricts the ```edit-profile``` route to authenticated members.

If an unauthorized user tries to access a route, a ```Mendo\Router\Acl\Exception\NotAuthorizedException``` exception will be thrown, with a 403 exception code.

## Installation

You can install Mendo Router using the dependency management tool [Composer](https://getcomposer.org/).
Run the *require* command to resolve and download the dependencies:

```
composer require mendoframework/router-acl
```