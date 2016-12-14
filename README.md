InfluxDbPresetBundle
====================

[![PHP Version](https://img.shields.io/badge/PHP-%5E7.0-blue.svg)](https://img.shields.io/badge/PHP-%5E7.0-blue.svg) [![Build Status](https://travis-ci.org/Yproximite/influxdb-preset-bundle.svg?branch=master)](https://travis-ci.org/Yproximite/influxdb-preset-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Yproximite/influxdb-preset-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Yproximite/influxdb-preset-bundle/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/30d897e5-749a-4b2e-aa5f-381c61ddebb6/mini.png)](https://insight.sensiolabs.com/projects/30d897e5-749a-4b2e-aa5f-381c61ddebb6)

InfluxDbPresetBundle: send metrics to InfluxDB server based on `Events` 

Since it relies on the great official [influxdb-php](https://github.com/influxdata/influxdb-php) library client (via the [Symfony bundle](https://github.com/Algatux/influxdb-bundle)) you can configure the latter to benefit from:
- Send multiple metrics at once (batch sending)
- Udp Events (sends the metrics using UDP)
- Http Events (sends the metrics to the InfluxDB API over HTTP)

Both methods (Udp/Http) can also be deferred, meaning you can send the metrics only when `kernel.terminate` event is fired in order not to slow your application.
You can read more on the documentation of [influxdb-bundle](https://github.com/Algatux/influxdb-bundle#sending-data-to-influx-db-trough-events)

![profiler_influx](https://cloud.githubusercontent.com/assets/9335422/21149456/d2f74b1c-c15b-11e6-9f89-eb7a2fabb754.png)

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
    default_profile_name: default # by default it's "default"
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
                app.exception:
                    measurement: app
                    tags: { metric_type: exception, code: "<value>" }
                app.page_views:
                    measurement: app
                    tags: { metric_type: page_views }
        other:
            connections:
                default:
                    protocol: http
            presets:
                app.response_time:
                    measurement: app
                    tags: { metric_type: response_time }
                app.order.requested:
                    measurement: orders
                    tags: { action: requested, delivery: false }
                    fields: { extra_value: true }
    extensions:
        memory:
            enabled: true
            preset_name: app.memory_usage
        response_time:
            enabled: true
            preset_name: app.response_time
            profile_name: other # by default it's "default"
        exception:
            enabled: true
            preset_name: app.exception
        request_count:
            enabled: true
            preset_name: app.page_views

# influx_db:
#     default_connection:   ~
#     connections:
#         default:
#             host:                 influxdb.example.com
#             database:             my_db
#             udp:                  true
#             udp_port:             4444
#             http_port:            8086
#         other:
#             host:                 important.example.com
#             database:             my_db
#             udp:                  false
#             udp_port:             4444
#             http_port:            8086
```

Usage
-----

### Sending ###

through events:

```php
use Yproximite\Bundle\InfluxDbPresetBundle\Event\InfluxDbEvent;

// Symfony\Component\EventDispatcher\EventDispatcherInterface
$eventDispatcher = $this->get('event_dispatcher');

// Preset from default profile
$eventDispatcher->dispatch('app.user.created', new InfluxDbEvent(1));

// Advanced event parameters
$event = new InfluxDbEvent(
    $value = 1, // will be converted to float
    string $profileName = 'other',
    ?\DateTimeInterface $dateTime = new \DateTime()
);

$eventDispatcher->dispatch('app.order.requested', $event);
```

using the client:

```php
// Yproximite\Bundle\InfluxDbPresetBundle\Client\ClientInterface
$client = $this->get('yproximite.influx_db_preset.client.client');

$client->sendPoint(
    string $profileName = 'other',
    string $presetName = 'app.user.created',
    float $value = 0.5,
    ?\DateTimeInterface $dateTime = new \DateTime()
);
```

### Listening for events ###

you can listen each client request through `ClientRequestEvent`:

```php
use Yproximite\Bundle\InfluxDbPresetBundle\Event\ClientRequestEvent;

final class MyAppListener
{
    public function onClientRequest(ClientRequestEvent $event)
    {
        dump(
            $event->getProfileName(),
            $event->getPresetName(),
            $event->getValue(),
            $event->getDateTime()
        );
    }
}
```

do not forget to add the following code into `services.yml`:

```yaml
services:
    app.event_listener.my_app_listener:
        class: AppBundle\EventListener\MyAppListener
        tags:
            - { name: kernel.event_listener, event: yproximite.bundle.influx_db_preset.client_request, method: onClientRequest }
```

You can enable `extensions` that will automatically (see configuration example) send the metrics for the memory usage, 
how much time the `Request/Response` cycle last, the status code of errors and the quantity of page views.
