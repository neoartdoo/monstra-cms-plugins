<?php

    /**
     *  Star Rating plugin
     *
     *  @package Monstra
     *  @subpackage Plugins
     *  @author Romanenko Sergey / Awilum
     *  @copyright 2012 Romanenko Sergey / Awilum
     *  @version 1.1.0
     *
     */


    // Register plugin
    Plugin::register( __FILE__,                    
                    __('Star Rating', 'starrating'),
                    __('Star Rating plugin for Monstra', 'starrating'),  
                    '1.1.0',
                    'Awilum',                 
                    'http://monstra.org/');

    if ( ! BACKEND) {

        // Add Stylesheet
        Stylesheet::add('plugins/starrating/starrating/jquery.rating.css', 'frontend', 15);

        // Add Javascript
        Javascript::add('plugins/starrating/starrating/jquery.rating.pack.js', 'frontend', 15);

    }


    /**
     * Star Rating Class
     */
    class StarRating {


        /**
         * Get Star Rating
         */
        public static function get() {

            // Init vars
            $marks = array();
            $_marks = array();
            $sr_page_uri_str = '';

            // Star rating directories
            $sr_dir = STORAGE . DS . 'starrating';      
             
            // Get uri
            $uri = Uri::segments();
            
            if (isset($uri[0]) && $uri[0] == '') $uri[0] = Option::get('defaultpage');

            foreach ($uri as $part) {
                $sr_page_uri_str .= $part.'.';
            }

            $sr_page_uri_str = substr($sr_page_uri_str, 0, -1);

            // Create page rating table and load it
            if ( ! File::exists($sr_dir . DS . $sr_page_uri_str . 'table.xml')) {
                Table::configure('tables_dir', $sr_dir);
                Table::create($sr_page_uri_str, array('mark', 'ip'));
                $sr_xml = new Table($sr_page_uri_str);
            } else {
                $sr_xml = new Table($sr_page_uri_str);
            }

            // Vote!
            if (Request::post('sr_vote')) {
                $mark_ip = $sr_xml->select('[ip="'.$_SERVER['REMOTE_ADDR'].'"]', 'all');                                   
                if (empty($mark_ip)) $sr_xml->insert(array('mark' => Request::post('star'), 'ip' => $_SERVER['REMOTE_ADDR']));
            }

            // Select marks
            $marks = $sr_xml->select();

            foreach ($marks as $_m) {
                $_marks[] = (int)$_m['mark'];           
            }       

            // Count mark
            if ( ! empty($marks)) $sum = array_sum($_marks) / count($_marks); else $sum = 0;

            // Render Star Rating 
            echo '
            <form method="post" action="" id="sr_form">
            <input name="star" type="radio" value="1" class="star" '; if ((int)$sum == 1) echo 'checked="checked"';  echo '/>
            <input name="star" type="radio" value="2" class="star" '; if ((int)$sum == 2) echo 'checked="checked"';  echo '/>
            <input name="star" type="radio" value="3" class="star" '; if ((int)$sum == 3) echo 'checked="checked"';  echo '/>
            <input name="star" type="radio" value="4" class="star" '; if ((int)$sum == 4) echo 'checked="checked"';  echo '/>
            <input name="star" type="radio" value="5" class="star" '; if ((int)$sum == 5) echo 'checked="checked"';  echo '/>
            <input type="submit" class="sr_vote" name="sr_vote" value="'.__('Vote', 'starrating').'">
            </form> <span class="sr_desc">'.__('Rating', 'starrating').': '.$sum.' /  '.__('Votes', 'starrating').': '.count($_marks).'</span>
            ';

        }

    }