![Logo](logo.png)

WordPress sample installation & resources

---

* [Apache configuration](#apache-configuration)
* [WordPress configuration](#wordpress-configuration)
* [Plugins](#plugins)
* [Online tools](#online-tools)

## Apache configuration

### Mandatory modules

| Module | Path | Description |
| --- | --- | --- |
| `php5_module` | `libexec/apache2/libphp5.so` | PHP support |
| `rewrite_module` | `libexec/apache2/mod_rewrite.so` | URL rewriting support |

### Additional modules

| Module | Path | Description |
| --- | --- | --- |
| `ssl_module` | `libexec/apache2/mod_ssl.so` | HTTPS support |
| `env_module` | `libexec/apache2/mod_env.so` | Environment variables support |
| `dir_module` | `libexec/apache2/mod_dir.so` | Configuration of directory index files |
| `mime_module` | `libexec/apache2/mod_mime.so` | Association between file extensions and mime types |
| `auth_basic_module` | `libexec/apache2/mod_auth_basic.so` | HTTP authentication |

### Virtual host

* Support of `.htaccess` enabled
* Directory indexing disabled

```
<VirtualHost *:80>
    ServerName wp-awesomess.dev
    DocumentRoot /path/to/wp-awesomess/app
    <Directory /path/to/wp-awesomess/app>
        AllowOverride all
        Options -Indexes
    </Directory>
</VirtualHost>
```

## WordPress configuration

Enabling PHP notices

```php
error_reporting(-1);
define('WP_DEBUG', true);
define('WP_DEBUG_DISPLAY', true);
```

Disabling user file modifications

```php
define('DISALLOW_FILE_EDIT', true);
```

Disabling all file modifications (including core updates, etc)

```php
define('DISALLOW_FILE_MODS', true);
```

## Plugins

* [query-monitor](https://github.com/johnbillion/query-monitor) - Monitoring database queries, hooks, conditionals, HTTP requests, query vars, environment, redirects, and more
* [http-auth](https://github.com/johansatge/http-auth) - HTTP auth management

## Online tools

* [secret-keys](https://api.wordpress.org/secret-key/1.1/salt/) - Online secret keys generator
* [GenerateWP](https://generatewp.com/) - Code generators
