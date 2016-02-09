<?php
namespace Requirejs\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use Requirejs\View\Helper\RequireHelper;

/**
 * Provide access to private members and methods
 */
//@codingStandardsIgnoreStart
class ViewTest extends View
{
    public function pluginSplit($name, $fallback = true)
    {
        return pluginSplit($name);
    }
}
//@codingStandardsIgnoreEnd

/**
 * Requirejs\View\Helper\requireHelper Test Case
 */
class RequireHelperTest extends TestCase
{

    public function testInitialization()
    {
        $require = new RequireHelper(new ViewTest());
        $this->assertContains('Html', $require->helpers);
        $this->assertContains('Url', $require->helpers);
    }

    public function testLoad()
    {
        $require = new RequireHelper(new ViewTest());
        $result = $require->load();
        $expected = '<script src="/requirejs/js/require.js"></script>';
        $this->assertContains($expected, $result);
    }

    public function testLoadConfig()
    {
        $require = new RequireHelper(new ViewTest());
        $result = $require->load(['Test.config']);
        $expected = 
"<script>
//<![CDATA[
require(['/test/js/config.js'], function(){require([]);});
//]]>
</script>";
        $this->assertContains($expected, $result);
    }

    public function testGetInlineConfig()
    {
        $require = new RequireHelper(new ViewTest(),[
            'inlineConfig' => [
                'baseUrl' => '/',
            ]
        ]);
        $result = $require->load();
        $expected = 
'<script>
//<![CDATA[
var require = {"baseUrl":"/"}
//]]>
</script>';
        $this->assertContains($expected, $result);
    }

    public function testConfigFiles()
    {
        $require = new RequireHelper(new ViewTest(),[
            'configFiles' => [
                'Test.config',
            ],
        ]);
        $result = $require->load();
        $expected = 
"<script>
//<![CDATA[
require(['/test/js/config.js'], function(){require([]);});
//]]>
</script>";
        $this->assertContains($expected, $result);
    }

    public function testModules()
    {
        $require = new RequireHelper(new ViewTest());
        $require->module('ModuleA');
        $require->module('ModuleB');
        $result = $require->load();
        $expected = 
"<script>
//<![CDATA[
require([''], function(){require(['ModuleA','ModuleB']);});
//]]>
</script>";
        $this->assertContains($expected, $result);
    }

    /**
     * Test _preLoadModule() method
     *
     * @return void
     */
    public function testPreLoadModule()
    {
        $require = new RequireHelper(new ViewTest());
        $result = $require->module('ModuleA');
        $this->assertEquals($result, '');
        $result = $require->load();
        $result = $require->module('ModuleB');
        $this->assertEquals($result, '<script>require(["ModuleB"]);</script>');
    }
}
