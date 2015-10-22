<?php

namespace Requirejs\View\Helper;

use Cake\Core\Configure;
use Cake\Utility\Inflector;
use Cake\View\Helper;

class RequireHelper extends Helper
{
    
    /**
     * Base Cakephp helpers
     */
    public $helpers = ['Html', 'Url'];

    /**
     * Fetch all previously loaded modules and requirejs lib path and outputs the
     * `<script>` tag to initialize the loader.
     *
     * Parameters accept the plugin notation so it's possible to load the files
     * like this $this->Require->load('Requirejs.require', 'TwbsTheme.main');
     *
     * @param string $require the path to the require.js library
     * @param string $config path of the main config file if not bundled with require.js
     * @param array $plugins array of plugins that need to
     * @return string full `<script>` tag to initialize requirejs
     */
    public function load($require, $config = null, $plugins = [])
    {
        if ($config === null) {
            $config = $require;
        }

        $loader = $this->_getLoader($require, $config);
        $modules = $this->_getModules($config, $plugins);

        return $loader . $modules;
    }

    /**
     * Return a `<script>` block that initializes the requirejs main configuration
     * file and loads all modules that have been loaded.
     *
     * Note that the requirejs library must be loaded befor this block
     *
     * @param string $config path of the main config file
     * @param array $plugins array of plugins that need to
     * @return string content of the `<script>` tag that initialize requirejs
     */
    protected function _getModules($config, $plugins = [])
    {
        list($plugin, $config) = $this->_View->pluginSplit($config, false);

        $modules = '';
        if (!is_null($this->_View->get('requireModules'))) {
            $modules = implode(',', $this->_View->get('requireModules'));
        }

        foreach ($plugins as $key => $plugin) {
            $plugin = $this->Url->assetUrl(
                $plugin,
                ['pathPrefix' => Configure::read('App.jsBaseUrl'), 'ext' => '.js']
            );
            $plugins[$key] = $plugin;
        }
        array_unshift($plugins, $config);
        $dependencies = implode("', '", $plugins);

        $script = "require(['" . $dependencies . "'], function(){require([";
        $script .= $modules;
        $script .= "]);});";

        $output = $this->Html->scriptBlock($script);

        return $output;
    }

    /**
     * Builds the `<script>` block that loads the requirejs library.
     *
     * @param string $require path of the requirejs library
     * @param string $config path of the main config file
     * @return string content of the `<script>` tag that initialize requirejs
     */
    protected function _getLoader($require, $config)
    {
        $this->Url->theme = null;
        $config = $this->Url->assetUrl(
            $config,
            ['pathPrefix' => Configure::read('App.jsBaseUrl'), 'ext' => '.js']
        );
        $loader = $this->Html->script($require, [
            'data-main' => $config
        ]);
        return $loader;
    }

    /**
     * Add a javascript module to be loaded on the page.
     *
     * Every module that is called prior to the load() commant should be pre-loaded
     * and will be outputted along with the loader.
     *
     * Every module that comes after the loader, for example via ajax, should
     * be loaded right away by setting the "preLoad" option to false
     *
     * @param string $name name of the js module to load
     * @param bool $preLoad bundles the module with initial loader or not
     * @return void|string loader tag is outputted on post-load
     */
    public function module($name, $preLoad = true)
    {
        list($plugin, $path) = $this->_View->pluginSplit($name, false);

        if (!empty($plugin)) {
            $name = $this->Url->assetUrl(
                $name,
                ['pathPrefix' => Configure::read('App.jsBaseUrl'), 'ext' => '.js']
            );
        }
        if ($preLoad) {
            return $this->_preLoadModule($name);
        }

        return $this->_loadModule($name);
    }

    /**
     * Preload modules. This method keep module names as variables and outputs
     * them at the moment when requirejs library is loaded
     *
     * @param string $name name of the js module
     * @return void
     */
    protected function _preLoadModule($name)
    {
        if (!isset($this->_View->viewVars['requireModules'])) {
            $this->_View->viewVars['requireModules'] = [];
        }
        array_push($this->_View->viewVars['requireModules'], "'" . $name . "'");
    }

    /**
     * Load module in a `<script>` block. This method is useful once the requirejs
     * lib has already been loaded in the page
     *
     * @param string $name name of the js module
     * @return string the script block to load the js module
     */
    protected function _loadModule($name)
    {
        return '<script>' . 'require(["' . $name . '"]);' . '</script>';
    }
}
