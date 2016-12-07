YproximiteInfluxDbPresetBundle
========================

Preset extension for the [**influxdb-bundle**](https://github.com/Algatux/influxdb-bundle).

* [Installation](#installation)
* [Usage](#usage)

Installation
------------

Require
[`yproximite/influxdb-preset-bundle`](https://packagist.org/packages/yproximite/influxdb-preset-bundle)
to your `composer.json` file:

```json
$ composer require yproximite/influxdb-preset-bundle
```

Register the bundle in `app/AppKernel.php`:

```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Yproximite\Bundle\InfluxDbPresetBundle\YproximiteInfluxDbPresetBundle(),
    );
}
```

Enable the bundle's configuration in `app/config/config.yml`:

```yaml
# app/config/config.yml
yproximite_influx_db_preset:
    presets:
        app.user.created:
            measurement: app_user_created
            tags: { metric_type: counter }
            fields: { extra_value: true }
        app.post.updated:
            measurement: app_post_updated
        app.memory_usage:
            measurement: app_memory_usage
            tags: { metric_type: gauge }
        app.time:
            measurement: app_time
            tags: { metric_type: timing }
        app.exception:
            measurement: "app_exception_<value>"
            tags: { metric_type: counter }
    extensions:
        memory:
            enabled: true
            preset_name: app.memory_usage
        time:
            enabled: true
            preset_name: app.time
        exception:
            enabled: true
            preset_name: app.exception
```

Usage
-----

```php
use Yproximite\Bundle\InfluxDbPresetBundle\Event\InfluxDbEvent;

public function userCreateAction()
{
    $this->get('event_dispatcher')->dispatch('app.user.created', new InfluxDbEvent(1));
}
```
