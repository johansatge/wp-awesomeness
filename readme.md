# Wordpress awesomeness

* [Configuring Apache](#configuring-apache)
* [Working with multiple environments](#working-with-multiple-environments)
* [Securing the Wordpress files](#securing-the-wordpress-files)

## Configuring Apache

### Modules

The following modules should be enabled for Wordpress to work properly.

* `php5_module` (`libexec/apache2/libphp5.so`)
* `headers_module` (`libexec/apache2/mod_headers.so`)
* `rewrite_module` (`libexec/apache2/mod_rewrite.so`)

### Virtual host

Basic virtual host sample, with `.htaccess` support roughly enabled, and disabled directory indexing.

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

## Working with multiple environments

Environment variables, such as `DB_NAME` or `DB_PASSWORD`, should not be shared nor present in the project source files.

The sample installation uses a *git-ignored* `.environment` file, that must be created in the `app` directory, and filled like below:

```
DB_HOST=mysql
DB_NAME=wp_awesomeness
DB_USER=root
DB_PASSWORD=root
WP_DEBUG=1
AUTH_KEY=xxxxx
SECURE_AUTH_KEY=xxxxx
LOGGED_IN_KEY=xxxxx
NONCE_KEY=xxxxx
AUTH_SALT=xxxxx
SECURE_AUTH_SALT=xxxxx
LOGGED_IN_SALT=xxxxx
NONCE_SALT=xxxxx
```

The file is loaded in [`wp-config.php`](app/wp-config.php).

Security keys can be generated [here](https://api.wordpress.org/secret-key/1.1/salt/), and should be unique for each project and environment.

## Securing the Wordpress files

When using a VCS we may not want the Wordpress files to be edited.

Manual edition of files in `wp-admin` can be blocked this way:

```php
define('DISALLOW_FILE_EDIT', true);
```

Or, we may want to completely block file edition, including Wordpress updates:

```php
define('DISALLOW_FILE_MODS', true);
```

