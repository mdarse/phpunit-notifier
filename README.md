PHPUnit Notifier
================

![Demo](http://i.imgur.com/XvDBg1c.gif)

Use [composer](http://getcomposer.org/) to install on your project:

```shell
$ composer require --dev "mdarse/phpunit-notifier"
```
To set up, just register `PHPUnitNotifier\NotifierListener` in the listeners section of you `phpunit.xml`.

Example of PHPUnit XML configuration:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit>
    <testsuites>
        <testsuite name="Test Suite">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
    <listeners>
        <listener class="PHPUnitNotifier\NotifierListener"></listener>
    </listeners>
</phpunit>
```

There is no supported mean to globally setup the notifier for every project, but
here is a [dirty workaround](https://gist.github.com/mdarse/4028dffd9bb5fdb57889).
