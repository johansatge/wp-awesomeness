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

The plugin does the following:

* Closes comments and pings for all posts
* Removes the comments bubble from the [Admin Bar](https://codex.wordpress.org/Toolbar)
* Disables the `X-Pingback` HTTP header
* Disables the `pingback.ping` method from [XMLRPC](https://codex.wordpress.org/XML-RPC_Support)
* Removes entries from the admin menu:
    * Comments
    * Settings > Discussion