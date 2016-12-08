InfluxDbPresetBundle
====================

[![PHP Version](https://img.shields.io/badge/PHP-%5E7.0-blue.svg)](https://img.shields.io/badge/PHP-%5E7.0-blue.svg)  [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Yproximite/influxdb-preset-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Yproximite/influxdb-preset-bundle/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/30d897e5-749a-4b2e-aa5f-381c61ddebb6/mini.png)](https://insight.sensiolabs.com/projects/30d897e5-749a-4b2e-aa5f-381c61ddebb6)

InfluxDbPresetBundle: send metrics to InfluxDB server based on `Events` 

Since it relies on the great official [influxdb-php](https://github.com/influxdata/influxdb-php) library client (via the [Symfony bundle](https://github.com/Algatux/influxdb-bundle)) you can configure the latter to benefit from:
- Send multiple metrics at once (batch sending)
- Udp Events (sends the metrics using UDP)
- Http Events (sends the metrics to the InfluxDB API over HTTP)

Both methods (Udp/Http) can also be deferred, meaning you can send the metrics only when `kernel.terminate` event is fired in order not to slow your application.
You can read more on the documentation of [influxdb-bundle](https://github.com/Algatux/influxdb-bundle#sending-data-to-influx-db-trough-events)

This bundle is inspired from [StatsdBundle](https://github.com/M6Web/StatsdBundle)

* [Installation](#installation)
* [Configuration](#configuration)
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
        new Algatux\InfluxDbBundle\InfluxDbBundle(),
    );
}
```

Also, be sure that you followed the [configuration procedure for influxdb-bundle](https://github.com/Algatux/influxdb-bundle) since it uses it to take the pre-configured service to communicate with the InfluxDB server.

Configuration
-------------

Here is the configuration reference:

```yaml
# app/config/config.yml
yproximite_influx_db_preset:
    default_profile_name: other # by default it's "default"
    profiles:
        default:
            connections:
                default:
                    protocol: udp
                    deferred: true
            presets:
                app.user.created:
                    measurement: users
                    tags: { type: member, action: created, free: yes, foo: bar }
                    fields: { extra_value: true }
                api.company.created:
                    measurement: api
                    tags: { action: created, object: company }
                api.company.deleted:
                    measurement: api
                    tags: { action: deleted, object: company }
                app.memory_usage:
                    measurement: app_memory_usage
                    tags: { metric_type: memory }
                app.time:
                    measurement: app
                    tags: { metric_type: response_time }
                app.exception:
                    measurement: app
                    tags: { metric_type: exception, code: "<value>" }
    extensions:
        memory:
            enabled: true
            preset_name: app.memory_usage
        time:
            enabled: true
            preset_name: app.time
            profile_name: other # by default it's "default"
        exception:
            enabled: true
            preset_name: app.exception

#influx_db:
#    host:                 influxdb.example.com 
#    database:             my_db
#    udp:                  false
#    udp_port:             4444
#    http_port:            8086    
```

Usage
-----

```php
use Yproximite\Bundle\InfluxDbPresetBundle\Event\InfluxDbEvent;

public function userCreateAction()
{
    $user = new User();
    ...
    $this->get('event_dispatcher')->dispatch('app.user.created', new InfluxDbEvent(1));
    
    return new Response('User Created');
}
```

You can enable `extensions` that will automatically (see configuration example) send the metrics for the memory usage, 
how much time the `Request/Response` cycle last, and the status code of errors
