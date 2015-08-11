<?php

namespace Requirejs\View\Helper;

use Cake\Core\Configure;
use Cake\Utility\Inflector;
use Cake\View\Helper;

class RequireHelper extends Helper
{
    
    public $helpers = ['Html', 'Url'];

    public function load($require, $config = null)
    {
        if ($config === null) {
            $config = $require;
        }

        $loader = $this->_getLoader($require, $config);
        $modules = $this->_getModules($config);

        return $loader . $modules;
    }

    protected function _getModules($config)
    {
        list($plugin, $name) = $this->_View->pluginSplit($config, false);

        $modules = '';
        if (!is_null($this->_View->get('requireModules'))) {
            $modules = implode(',', $this->_View->get('requireModules'));
        }

        $script =  "require(['" . $name . "'], function(){require([";
        $script .= $modules;
        $script .= "]);});";

        $output = $this->Html->scriptBlock($script);

        return $output;
    }

    protected function _getLoader($require, $config)
    {
        $config = $this->Url->assetUrl(
            $config,
            ['pathPrefix' => Configure::read('App.jsBaseUrl'), 'ext' => '.js']
        );
        $loader = $this->Html->script($require, [
            'data-main' => $config
        ]);
        return $loader;
    }

    public function module($name, $preLoad = true)
    {
        list($plugin, $path) = $this->_View->pluginSplit($name, false);

        if (!empty($plugin)) {
            $name = $this->Url->assetUrl(
                $name,
                ['pathPrefix' => Configure::read('App.jsBaseUrl'), 'ext' => '.js']
            );
        }
        if($preLoad) {
            return $this->_preLoadModule($name);
        }

        return $this->_loadModule($name);
    }

    protected function _preLoadModule($name) 
    {
        if (!isset($this->_View->viewVars['requireModules'])) {
            $this->_View->viewVars['requireModules'] = [];
        }
        array_push($this->_View->viewVars['requireModules'], "'" . $name . "'");
    }

    protected function _loadModule($name) {
        return '<script>' .  'require(["' . $name . '"]);' .  '</script>';
    }
}
