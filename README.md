# Require plugin for CakePHP

Load javascript modules (AMD) asynchroneously from anywhere in your views
via [requirejs](http://requirejs.org/).

## Warning

this plugin is a work in progress

## Installation

Install the plugin using [composer](http://getcomposer.org). 

```
"gintonicweb/requirejs": "dev-master"
```

Load it in config/bootstrap.php

```
Plugin::load('Requirejs');
```

## Example


Add the following lines to your default layout `default.ctp`

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
    // configuration file which will be used as the entry point
    echo $require->load('Requirejs.require', 'Requirejs.config')
?>
```

