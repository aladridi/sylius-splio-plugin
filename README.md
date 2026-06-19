# Sylius Splio Plugin

Local Sylius plugin for integrating Splio CRM with a Sylius application.

## Features

- Admin configuration page for Splio API credentials
- Database-backed Splio settings
- Splio API client with OAuth token handling
- Contact manager service for upserting and fetching contacts
- Doctrine migration namespace registered by the plugin

## Installation

Add the plugin as a path repository in the Sylius application:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "plugins/Splio/SyliusSplioPlugin",
            "options": {
                "symlink": true
            }
        }
    ],
    "require": {
        "dridialaa/sylius-splio-plugin": "@dev"
    }
}
```

Enable the bundle:

```php
Dridialaa\SyliusSplioPlugin\DridialaaSyliusSplioPlugin::class => ['all' => true],
```

Import the plugin routes:

```yaml
dridialaa_sylius_splio:
    resource: '@DridialaaSyliusSplioPlugin/config/routes.yaml'
```

Run migrations:

```bash
php bin/console doctrine:migrations:migrate
```

## Configuration

The plugin reads fallback values from these environment variables:

```dotenv
SPLIO_BASE_URI=
SPLIO_AUTH_ENDPOINT=
SPLIO_CLIENT_ID=
SPLIO_CLIENT_SECRET=
SPLIO_TIMEOUT=10
```

The admin settings page is available at:

```text
/admin/splio/settings
```
