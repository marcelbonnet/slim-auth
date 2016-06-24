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

## History

This project started as a modified version of a development branch from jeremykendall/slim-auth.
