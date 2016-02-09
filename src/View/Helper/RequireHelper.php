<?php

namespace Requirejs\View\Helper;

use Cake\Core\Configure;
use Cake\Utility\Inflector;
use Cake\View\Helper;

class RequireHelper extends Helper
{
    
    /**
     * {@inherit}
     */
    public $helpers = ['Html', 'Url'];

    /**
     * {@inherit}
     */
    protected $_defaultConfig = [
        'require' => '//cdnjs.cloudflare.com/ajax/libs/require.js/2.1.22/require.js',
        'configFiles' => [],
        'inlineConfig' => []
    ];

    /**
     * List of pre-loaded modules
     */
    private $_modules = [];

    /**
     * Tracks if requirejs has been loaded
     */
    private $_requireLoaded = false;

    /**
     * Fetch all previously loaded modules and requirejs lib path and outputs the
     * `<script>` tag to initialize the loader.
     *
     * Parameters accept the plugin notation so it's possible to load the files
     * like this $this->Require->load('Requirejs.require', 'TwbsTheme.main');
     *
     * @param array $configFiles additional config files to load on the fly
     * @return string full `<script>` tag to initialize requirejs
     */
    public function load(array $configFiles = [])
    {
        $config = $configFiles + $this->config('configFiles');
        $this->config('configFiles', $configFiles);
        $inlineConfig = $this->_getInlineConfig();
        $loader = $this->_getLoader();
        $modules = $this->_getModules();
        $this->_requireLoaded = true;
        return $inlineConfig . $loader . $modules;
    }

    /**
     * Return a `<script>` block that initializes the requirejs main configuration
     * file and loads all modules that have been loaded.
     *
     * Note that the requirejs library must be loaded before this block
     *
     * @return string content of the `<script>` tag that initialize requirejs
     */
    protected function _getInlineConfig()
    {
        $script = "var require = ";
        $script .= json_encode($this->config('inlineConfig'), JSON_UNESCAPED_SLASHES);
        return $this->Html->scriptBlock($script);
    }
    /**
     * Return a `<script>` block that initializes the requirejs main configuration
     * file and loads all modules that have been loaded.
     *
     * Note that the requirejs library must be loaded before this block
     *
     * @return string content of the `<script>` tag that initialize requirejs
     */
    protected function _getModules()
    {
        $modules = implode(',', $this->_modules);

        $configFiles = $this->config('configFiles');
        foreach ($configFiles as $key => $config) {
            $config = $this->Url->assetUrl($config, [
                'pathPrefix' => Configure::read('App.jsBaseUrl'),
                'ext' => '.js'
            ]);
            $configFiles[$key] = $config;
        }

        $dependencies = implode("', '", $configFiles);

        $script = "require(['" . $dependencies . "'], function(){require([";
        $script .= $modules;
        $script .= "]);});";

        return $this->Html->scriptBlock($script);
    }

    /**
     * Builds the `<script>` block that loads the requirejs library.
     *
     * @return string content of the `<script>` tag that initialize requirejs
     */
    protected function _getLoader()
    {
        return $this->Html->script($this->config('require'));
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
     * @return void|string loader tag is outputted on post-load
     */
    public function module($name)
    {
        list($plugin, $path) = $this->_View->pluginSplit($name, false);
        if (!empty($plugin)) {
            $name = $this->Url->assetUrl($name, [
                'pathPrefix' => Configure::read('App.jsBaseUrl'),
                'ext' => '.js',
            ]);
        }
        if (!$this->_requireLoaded) {
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
        $this->_modules[] = "'" . $name . "'";
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
        return $this->Html->scriptBlock('require(["' . $name . '"]);');
    }
}
