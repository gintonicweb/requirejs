<?php
namespace Requirejs\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use Requirejs\View\Helper\RequireHelper;

/**
 * Provide access to private members and methods
 */
//@codingStandardsIgnoreStart
class RequireChild extends RequireHelper
{
    public $_View;
    public function _getModules($config)
    {
        return parent::_getModules($config);
    }
    public function _getLoader($require, $config)
    {
        return parent::_getLoader($require, $config);
    }
    public function _preLoadModule($name)
    {
        return parent::_preLoadModule($name);
    }
    public function _loadModule($name)
    {
        return parent::_loadModule($name);
    }
}
//@codingStandardsIgnoreEnd

/**
 * Requirejs\View\Helper\requireHelper Test Case
 */
class RequireHelperTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $view = new View();
        $this->Require = new RequireChild($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->require);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->assertContains('Html', $this->Require->helpers);
        $this->assertContains('Url', $this->Require->helpers);
    }

    /**
     * Test load() method
     *
     * @return void
     */
    public function testLoad()
    {
        $tag = $this->Require->module('ModuleA');
        $tag = $this->Require->load('require', 'config');

        $this->assertContains('src="/js/require.js"', $tag);
        $this->assertContains('data-main="/js/config.js"', $tag);
        $this->assertContains('require(', $tag);
        $this->assertContains("['config']", $tag);
        $this->assertContains("['ModuleA']", $tag);
    }

    /**
     * Test _getModules() method
     *
     * @return void
     */
    public function testGetModules()
    {
        $this->Require->_View->viewVars['requireModules'] = [
            "'ModuleA'",
            "'ModuleB'",
            "'ModuleC'",
        ];
        $this->assertContains(
            "require(['ModuleA','ModuleB','ModuleC']);",
            $this->Require->_getModules('config')
        );
    }

    /**
     * Test _getLoader() method
     *
     * @return void
     */
    public function testGetLoader()
    {
        $tag = $this->Require->load('require', 'config');

        $this->assertContains('src="/js/require.js"', $tag);
        $this->assertContains('data-main="/js/config.js"', $tag);
        $this->assertContains('require(', $tag);
        $this->assertContains("['config']", $tag);
    }

    /**
     * Test module() method
     *
     * @return void
     */
    public function testModule()
    {
        $result = $this->Require->module('ModuleA');
        $this->assertNull($result);
        $this->assertContains(
            "'ModuleA'",
            $this->Require->_View->viewVars['requireModules']
        );

        $result = $this->Require->module('ModuleB', false);
        $this->assertNotNull($result);
        $this->assertFalse(in_array(
            "'ModuleC'",
            $this->Require->_View->viewVars['requireModules']
        ));
    }

    /**
     * Test _preLoadModule() method
     *
     * @return void
     */
    public function testPreLoadModule()
    {
        $this->Require->_preLoadModule('ModuleA');
        $this->Require->_preLoadModule('ModuleB');

        $this->assertContains(
            "'ModuleA'",
            $this->Require->_View->viewVars['requireModules']
        );
        $this->assertContains(
            "'ModuleB'",
            $this->Require->_View->viewVars['requireModules']
        );
    }

    /**
     * Test _ladModule() method
     *
     * @return void
     */
    public function testLoadModule()
    {
        $result = $this->Require->_loadModule('ModuleA');
        $this->assertEquals('<script>require(["ModuleA"]);</script>', $result);
    }
}
