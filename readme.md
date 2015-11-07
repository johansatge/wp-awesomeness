![Logo](logo.png)

WordPress sample installation.

---

* [Apache configuration](#apache-configuration)
* [Plugins](#plugins)

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

## Plugins

### `disable-comments.php`

Disables comment support.

* Closes comments and pings for all posts
* Removes the comments bubble from the [Admin Bar](https://codex.wordpress.org/Toolbar)
* Disables the `X-Pingback` HTTP header
* Disables the `pingback.ping` method from [XMLRPC](https://codex.wordpress.org/XML-RPC_Support)
* Removes entries from the admin menu:
    * Comments
    * Settings > Discussion
    
### `disable-emojis.php`

Disables emoji support.

* Removes Javascript and CSS calls
* Disables the `wpemoji` TinyMCE plugin

### `http-auth.php`

Manages HTTP authentication.

The plugin can use [Basic](https://en.wikipedia.org/wiki/Basic_access_authentication) or [Digest](https://en.wikipedia.org/wiki/Digest_access_authentication) authentication  methods.

The configuration is done by setting the following constants in your configuration file:

| Constant | Default | Usage |
| --- | --- | --- |
| `HTTP_AUTH_USERS` | *empty* | List of `user:password` couples, separated by a `,`. If empty or not set, the plugin will do nothing. |
| `HTTP_AUTH_FRONTEND` | `false` | Enables auth on frontend |
| `HTTP_AUTH_BACKEND` | `true` | Enables auth on backend |
| `HTTP_AUTH_REALM` | `Restricted area` | Sets the [realm](http://tools.ietf.org/html/rfc1945#section-11) |
| `HTTP_AUTH_TYPE` | `Basic` | Sets the auth method (may be `Digest` or `Basic`) |

If you choose the *Basic* auth method, passwords have to be hashed with MD5.

```php
define('HTTP_AUTH_USERS', 'my_user:a865a7e0ddbf35fa6f6a232e0893bea4'); // my_user:my_password
```

If you use the *Digest* auth, they have to be stored in plaintext.

```php
define('HTTP_AUTH_USERS', 'my_user:my_password,my_user_2:my_password_2');
```

### `ip-restrictions.php`

Manages IP restrictions.

Define the allowed IP addresses, separated by `,`:

```php
define('IP_RESTRICTIONS_LIST', '127.0.0.1,12.34.56.78');
// If the constant is omitted or empty, the plugin will do nothing
```

Define when the IP restrictions should be applied *(optional)*:

```php
define('IP_RESTRICTIONS_FRONTEND', true); // Default is FALSE
define('IP_RESTRICTIONS_BACKEND', true); // Default is TRUE
```

Define a fallback *(optional)*:

```php
// You may set a message:
define('IP_RESTRICTIONS_FALLBACK', '<h1>You are not allowed to view this resource.</h1>');

// Or, set the path to a file which will be loaded (.html or .php):
define('IP_RESTRICTIONS_FALLBACK', '/path/to/wordpress/wp-content/my-custom-error-page.php');

// Default message is "<h1>403 Forbidden</h1>"
```

### `jquery-to-footer.php`

Moves jQuery and its dependencies in the footer.

The script moves the `/wp-includes/js/jquery/jquery.js` call in the footer.

Don't forget to check that other jquery-dependant files are properly enqueued.

Also, please note that the `jquery-migrate` script is no longer loaded.

### `mandatory-plugins.php`

Forces activation of specific plugins.

#### Why

Sometimes we want specific plugins to be enabled no matter what. They can be:

* Plugins needed by the theme
* Libraries (like [ACF](www.advancedcustomfields.com))
* Security plugins
* And so on

This mu-plugin allows to set in the code a list of mandatory plugins, and for each one:

* Activates it if needed, when WP loads
* Removes its *Deactivate* link in the *Plugins* page
* Blocks its manual deactivation

> :warning:
>
> When enabling plugins, WordPress checks their validity (if they are going to display notices or crash the website, for instance).
>
> This mu-plugin does not, so you should only use it with plugins that have already been tested in your project.

#### Usage

Mandatory plugins are stored in the `MANDATORY_PLUGINS` constant.

* Entries must be separated by a comma (`,`)
* Each entry must point to a valid plugin file

Sample usage:

```php
define('MANDATORY_PLUGINS', implode(',', array(
    'advanced-custom-fields/acf.php',
    'visual-form-builder/visual-form-builder.php',
    'better-search-replace/better-search-replace.php',
)));
```

### `roles-loader.php`

Override easily user roles and menus.

The plugin tries to load a `roles.php` file in the `wp-content` directory.

Here is a file example:

```php
<?php

defined('ABSPATH') or die('Cheatin\' uh?');

return array(
    'administrator' => array(
        'name'         => 'Administrator',
        'restricted_screens' => array(),
        'capabilities' => array(
            'activate_plugins'       => true,
            'delete_others_pages'    => true,
            'delete_others_posts'    => true,
            'delete_pages'           => true,
            // [...]
            'create_users'           => true,
            'delete_users'           => true,
            'unfiltered_html'        => true
        )
    ),
    'contributor' => array(
        'name'         => 'Contributor',
        'restricted_screens' => array(
            'tools.php',
            'themes.php:widgets.php'
        ),
        'capabilities' => array(
            'delete_pages'           => true,
            // [...]
            'unfiltered_html'        => true
        )
    )
);
```

The file has to return an `array` of roles that are made of the following parameters:

#### A name

The role name.

For now, the plugin does not allow to translate it.

### A set of capabilities

The [capabilities](https://codex.wordpress.org/Roles_and_Capabilities#Capabilities) may be native ones,
or specific to the application.

#### A set of *restricted screens*

Sometimes capabilities can't target the pages we want to disable in the admin menu.

If so, it is possible to add them in the `restricted_screens` key of the needed role.

The entry has to be the path of the page. Here are some examples:

* `tools.php`: The *Tools* first-level menu
* `themes.php:widgets.php`: The *Widgets* page, in the *Appearance* first-level menu
* `options-general.php:options-writing.php`: The *Writing page*, in the *Settings* first-level menu