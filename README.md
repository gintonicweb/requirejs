[![Build Status](https://travis-ci.org/gintonicweb/requirejs.svg)](https://travis-ci.org/gintonicweb/GintonicCMS)
[![Coverage Status](https://coveralls.io/repos/gintonicweb/requirejs/badge.svg?branch=master&service=github)](https://coveralls.io/github/gintonicweb/requirejs?branch=master)
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

```
public $helpers = [
    'Requirejs.Require' => [

        // the basepath where requirejs library can be found (optional)
        'require' => 'Requirejs.require',

        // requirejs configuration files (optional)
        'configFiles' => [
            'TwbsTheme.config',
            'Images.config',
        ],

        // inline configuration options, echoed as an inline config file (optional)
        'inlineConfig' => [
            'baseUrl' => '/',
        ],
    ],
];
```

In your default layout: 

```
<?php 
    // Here's how to define a javascript dependency, with support for
    // CakePHP plugin notation. The following will load
    // '/requirejs/js/app/demo.js'
    $require->module('Requirejs.app/demo');

    // When no plugins are defined, the module name is passed as-is to requirejs
    // which allows us to alias the paths in our config.js.
    $require->module('test/demo');

    // At the bottom of the layout, load the requirejs library
    echo $require->load()
?>
```

