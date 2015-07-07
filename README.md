# WP Bootstrap

My sample Wordpress installation.

## Virtual host

```
<VirtualHost 127.0.0.1:80>
    DocumentRoot ${WWW_PATH}/wp-bootstrap/app
    ServerName wp-bootstrap.dev
    <Directory ${WWW_PATH}/wp-bootstrap/app>
        AllowOverride all
    </Directory>
</VirtualHost>
```

## Configured users

| Username | Password | Role
| --- | --- | --- |
| `admin` | `admin` | Administrator |
