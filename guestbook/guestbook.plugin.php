<?php

    /**
     *  Guestbook plugin
     *
     *  @package Monstra
     *  @subpackage Plugins
     *  @author Romanenko Sergey / Awilum
     *  @copyright 2012 Romanenko Sergey / Awilum
     *  @version 1.1.1
     *
     */


    // Register plugin
    Plugin::register( __FILE__,                    
                    __('Guestbook', 'guestbook'),
                    __('Guest book plugin for Monstra', 'guestbook'),  
                    '1.1.1',
                    'Awilum',                 
                    'http://monstra.org/',
                    'guestbook');


    // Load Guestbook Admin for Editor and Admin
    if (Session::exists('user_role') && in_array(Session::get('user_role'), array('admin', 'editor'))) {        
        
        Plugin::admin('guestbook');

    }


    /**
     * Guestbook class
     */
    class Guestbook extends Frontend {

        /**
         * Guestbook Records content
         *
         * @var string
         */
        public static $guestbook_records = '';


        /**
         * Guestbook Form content
         *
         * @var string
         */
        public static $guestbook_form = '';


        /**
         * Guestbook table
         */
        public static $guestbook = null;
        

        /**
         * Guestbook main functions
         */
        public static function main() {
            
            // Get guestbook table
            Guestbook::$guestbook = new Table('guestbook');

            // Select all records
            $records = Guestbook::$guestbook->select(null, 'all');

            // Get post data
            $username = Request::post('guestbook_username'); 
            $email    = Request::post('guestbook_email');                    
            $message  = Request::post('guestbook_message'); 

            $errors = array();

            // Add new record
            if (Request::post('guestbook_submit')) {
                
                if (Request::post('guestbook_username') == '' || Request::post('guestbook_email') == '' || Request::post('guestbook_message') == '') {
                    $errors['guestbook_empty_fields'] = __('Empty required fields!', 'guestbook');
                }

                if ( ! Valid::email(Request::post('guestbook_email'))) {
                    $errors['guestbook_email_not_valid'] = __('Email address is not valid!', 'guestbook');
                }

                if ( ! Captcha::correctAnswer($_POST['answer'])) {
                    $errors['guestbook_captcha_not_valid'] = __('Captcha answer is wrong!', 'guestbook');
                }

                if (count($errors) == 0) {
                    Guestbook::$guestbook->insert(array('username' => $username, 'email' => $email, 'message' => $message, 'date' => time()));
                    Request::redirect(Option::get('siteurl').'guestbook');
                }

            }

            // Get index view
            Guestbook::$guestbook_records = View::factory('guestbook/views/frontend/index')
                        ->assign('records', $records)
                        ->render();

            // Get form view
            Guestbook::$guestbook_form = View::factory('guestbook/views/frontend/form')
                        ->assign('username', $username)
                        ->assign('email', $email)
                        ->assign('message', $message)
                        ->assign('errors', $errors)
                        ->render();
        }

        /**
         * Set Guestbook title
         */
        public static function title() {
            return __('Guestbook', 'guestbook');
        }

        /**
         * Set Guestbook content
         */
        public static function content() {
            return Guestbook::$guestbook_records.Guestbook::$guestbook_form;
        }

        /**
         * Set Guestbook template
         */
        public static function template() {
            return Option::get('guestbook_template');
        }
    }
