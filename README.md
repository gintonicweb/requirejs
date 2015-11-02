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
"gintonicweb/requirejs": "~0.1"
```

Load it in config/bootstrap.php

```
Plugin::load('Requirejs');
```

## Example


In your default layout: 

```
<?php 
    // Load the helper provided in this plugin
    $require = $this->loadHelper('Requirejs.Require');

    // Here's how to define a javascript dependency, with support for
    // CakePHP plugin notation. The following will load
    // '/requirejs/js/app/demo.js'
    $require->module('Requirejs.app/demo');

    // When no plugins are define, the module name is passed as-is to requirejs
    // which allows us to alias the paths in our config.js file.
    $require->module('test/demo');

    // At the bottom of the layout, load the requirejs library with the 
    // configuration file which will be used as the entry point.
    echo $require->load('Requirejs.require', 'Requirejs.config')

    // If you happen to have your configuration spread across multiple
    // files, you can add them like this
    echo $require->load('Requirejs.require', 'Requirejs.config', ['SomePlugin.config'])
?>
```

