# Slim Framework Authentication and Authorization Middleware

This project uses parts of Zend Framework, like Zend Auth classes, Zend ACL and  Zend config.

My goal was to build a reusable Middleware to authenticate through LDAP and/or RDBMS.

# Install

```sh
$ composer require marcelbonnet/slim-auth
```

# Bundled Mechanism

- Authentication through LDAP (see sample config for LDAP and AD)
- Authentication through RDBMS
- Authorization based on user roles kept in a RDBMS' table.

## RDBMS Mechanism

Agnostic table design. The only thing slim-auth needs to know is where users and roles are stored , using an instance of Doctrine ORM's EntityManager. 

# Requirements

- Slim Framework v. 3.x
- PHP >= 5.6
- Doctrine ORM >= 2.5

To see all dependencies: https://packagist.org/packages/marcelbonnet/slim-auth

# How To (slim-auth min version 2.0.0)

1.x not compatible.

## Dao

This package suppose you have a User ```0..+``` Role(s). Here an example design (use whatever attribute names you want):

```php

class User {

    protected $username;
    protected $passwordHash;
    /**
    @OneToMany(targetEntity="Role", ...)
    */
    protected $roles;
}

class Role {
    /**
    The role name
    @var string
    */
    protected $role;
    /**
    @ManyToOne(targetEntity="User")
    */
    protected $user;
}
```

## index.php

```php
use marcelbonnet\Slim\Auth\ServiceProvider\SlimAuthProvider;
use Zend\Authentication\Storage\Session as SessionStorage;
use marcelbonnet\Slim\Auth\Middleware\Authorization;
use marcelbonnet\Slim\Auth\Handlers\RedirectHandler;
use marcelbonnet\Slim\Auth\Adapter\LdapRdbmsAdapter;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Http\Request as SlimHttpRequest;
use \Slim\Http\Response as SlimHttpResponse;

require_once 'vendor/autoload.php';

/* ****************************************************************************
 * Slim App and Config
 * ****************************************************************************
 */
$config = require '../conf/config.php';
$app = new \Slim\App($config);

// Fetch DI Container
$container = $app->getContainer();

/* ****************************************************************************
 * Authentication/Authorization
 * ****************************************************************************
 */
$acl = new Acl();
//ACLed Slim Route
$container['router'] = new \marcelbonnet\Slim\Auth\Route\AuthorizableRouter(null, $acl);
$container['acl']    = $acl;

$adapterOptions = [];
//if you want auth to be valid if some column exists with an expected value:
// $adapterOptions = [
//              'checkUserIsActivated'  => 'my_column_in_user_table',
//              'userIsActivatedFlag'       => true
//      ];
$adapter = new marcelbonnet\Slim\Auth\Adapter\LdapRdbmsAdapter(
        '/some/file.conf',  //LDAP config or NULL if not using LDAP
        $myEntityManager, //an Doctrine's Entity Manager instance 
        "\Your\Project\Dao\Role",    //Role class
        "role", //Role's class role attribute
        "user", //Role's class user attribute (the @ManyToOne attrib)
        "\Your\Project\Dao\User", //User class
        "username", //User name attribute
        "passwordHash", //password (as a hash) attribute
        marcelbonnet\Slim\Auth\Adapter\LdapRdbmsAdapter::AUTHENTICATE_RDBMS, //auth method: LdapRdbmsAdapter::AUTHENTICATE_RDBMS | LdapRdbmsAdapter::AUTHENTICATE_LDAP 
        10, //a hash factor
        PASSWORD_DEFAULT, //hash algorithm
        $adapterOptions //if needed
        );

$container["authAdapter"] = $adapter;

$slimAuthProvider = new SlimAuthProvider();
$slimAuthProvider->register($container);

$app->add(new Authorization( $container["auth"], $acl, new RedirectHandler("auth/notAuthenticated", "auth/notAuthorized") ));
# checks:
#$username=(is_array(@$c["auth"]->getStorage()->read()))? @$c["auth"]->getStorage()->read()["username"] : @$c["auth"]->getStorage()->read();
#$userRoles=(is_array(@$c["auth"]->getStorage()->read()))? @$c["auth"]->getStorage()->read()["role"] : array();

/**
    Example Routes: you must set allowed Roles (as one string or as an array or string roles) for each route. 
*/
$app->get('/', 'My\Controller:home' )->setName("home")->allow(Acl::MEMBER);

$app->get('/home', function (SlimHttpRequest $request, SlimHttpResponse $response, $args) use($container) {
    $container->get('router')->getNamedRoute('home')->run($request, $response);
})->allow(Acl::MEMBER);


$app->get('/hello[/{name}]', 'My\Controller:sayHello')->setName('hello')->allow([Acl::GUEST, Acl::MEMBER]);
$app->get('/protected', 'My\Controller:callProtectedResource')->setName('protected')->allow(Acl::ADMIN);

$app->run();
```

Now your ACL class should look like:

```php
class Acl extends SlimAuthAcl
{
    const GUEST                     = "guest";
    const ADMIN                     = "admin";
    const MEMBER                    = "member";
    

    public function __construct()
    {
        // APPLICATION ROLES
        $this->addRole(self::GUEST);
        
        $this->addRole(self::MEMBER, self::GUEST);
        
        /* **************************************
         * WARNING: ALLOW ALL:
         * **************************************
         */
        $this->addRole(self::ADMIN);
        $this->allow(self::ADMIN);
    }
    
    
}
```

## How it looks in MySQL

```sql
mysql> SELECT * FROM core__users;
+----+--------------+--------------------------------------------------------------+
| id | username     | passwordHash                                                 |
+----+--------------+--------------------------------------------------------------+
|  2 | marcelbonnet | $2y$15$9b9Vb5K/Rcg.s6Gjn0cpnu4iAhRdbWA0lIxqzf5mLl81WW.qYtXzK |
+----+--------------+--------------------------------------------------------------+

mysql> SELECT * FROM core__user_roles;
+----+------------+--------+
| id | fk_user_id | role   |
+----+------------+--------+
|  5 |          2 | admin  |
|  3 |          2 | member |
+----+------------+--------+
```

## History

This project started as a modified version of a development branch from jeremykendall/slim-auth.
