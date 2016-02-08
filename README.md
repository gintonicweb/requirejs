[![Build Status](https://travis-ci.org/gintonicweb/requirejs.svg)](https://travis-ci.org/gintonicweb/GintonicCMS)
[![codecov.io](https://codecov.io/github/gintonicweb/requirejs/coverage.svg?branch=master)](https://codecov.io/github/gintonicweb/requirejs?branch=master)
[![Packagist](https://img.shields.io/packagist/dt/gintonicweb/requirejs.svg)]()
[![Software License](https://img.shields.io/github/license/mashape/apistatus.svg)](LICENSE)

# Require plugin for CakePHP

Load javascript modules (AMD) asynchroneously from anywhere in your views
via [requirejs](http://requirejs.org/).

## Installation

Install the plugin using [composer](http://getcomposer.org). 

```
"gintonicweb/requirejs": "~0.2"
```

Load it in config/bootstrap.php

```
Plugin::load('Requirejs');
```

## Example

Load this helper from your controller and define your config options.

- `require` the basepath where requirejs library can be found (optional)
- `configFiles` requirejs configuration files (optional)
- `inlineConfig` inline configuration options, echoed as an inline config file (optional)

```
public $helpers = [
    'Requirejs.Require' => [
        'require' => 'Requirejs.require',
        'configFiles' => [
            'TwbsTheme.config',
            'Images.config',
        ],
        'inlineConfig' => [
            'baseUrl' => '/',
        ],
    ],
];
```

Here's how to define a javascript dependency. CakePHP plugin notation is supported. The following will load `'/requirejs/js/app/demo.js'`
```
<?= $require->module('Requirejs.app/demo') ?>
```

When no plugins are defined, the module name is passed as-is to requirejs, allows you to handle the path the way you like in config.js.
```
<?= require->module('test/demo') ?>
```

Load requirejs somewhere in your template. You'll usually want to keep that at the bottom of your layout.
```
<?= $require->load() ?>
```
