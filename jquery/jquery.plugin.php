<?php

    /**
     *  jQuery plugin
     *
     *  @package Monstra
     *  @subpackage Plugins
     *  @author Romanenko Sergey / Awilum
     *  @copyright 2011 - 2012 Romanenko Sergey / Awilum
     *  @version 1.0.1
     *
     */


    // Register plugin
    Plugin::register( __FILE__,                    
                    __('jQuery'),
                    __('jQuery plugin for Monstra'),  
                    '1.0.1',
                    'Awilum',
                    'http://monstra.org/');


    Action::add('theme_footer', 'jQueryTheme');


    function jQueryTheme($version = '1.7.1') {
        echo("<script>
                var jQueryScriptOutputted = false;
                function initJQuery() {
                    if (typeof(jQuery) == 'undefined') {
                        if ( ! jQueryScriptOutputted) {
                            jQueryScriptOutputted = true;
                            document.write('<scr' + 'ipt src=http://ajax.googleapis.com/ajax/libs/jquery/{$version}/jquery.min.js ></scr' + 'ipt>');
                        }
                        setTimeout('initJQuery()', 50);
                    } else {
                        $(function(){});
                    }
                }
                initJQuery();
            </script>");
    }