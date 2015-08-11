<?php
namespace Require\View\Helper;

use Cake\Utility\Inflector;
use Cake\View\Helper;

class RequireHelper extends Helper
{
    
    /**
     * TODO: doccomment
     */
    public function load()
    {
        $modules = '';
        if (!is_null($this->_View->get('requiredeps'))) {
            $modules = "require([" . implode(',', $this->_View->get('requiredeps')) . "]);";
        }
        $output = '<script src="/js/main.js" data-main="js/main"></script>';
        $output .= "<script type='text/javascript'>";
        $output .= "require(['main'], function () {";
        $output .= $modules;
        $output .= '});</script>';
        return $output;
    }

    /**
     * TODO: doccomment
     */
    public function req($name)
    {
        if (!isset($this->_View->viewVars['requiredeps'])) {
            $this->_View->viewVars['requiredeps'] = [];
        }
        array_push($this->_View->viewVars['requiredeps'], "'" . $name . "'");
        return;
    }
    
    /**
     * TODO: doccomment
     */
    public function ajaxReq($name)
    {
        return '<script>' .
            'require(["' . $name . '"]);' .
            '</script>';
    }
}
