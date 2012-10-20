<?php 

    // Admin Navigation: add new item
    Navigation::add(__('Guestbook', 'guestbook'), 'content', 'guestbook', 4);

    // Add actions
    Action::add('admin_themes_extra_index_template_actions','GuestbookAdmin::formComponent');
    Action::add('admin_themes_extra_actions','GuestbookAdmin::formComponentSave');
    
    
    class GuestbookAdmin extends Backend {

        /**
         * Main Sandbox admin function
         */
        public static function main() {

            // Get guestbook table
            $guestbook = new Table('guestbook');

            // Select all records
            $records = $guestbook->select(null, 'all');

            // Delete record
            if (Request::get('action') &&  Request::get('action') == 'delete_record' && Request::get('record_id')) {
                $guestbook->delete((int)Request::get('record_id'));
                Request::redirect('index.php?id=guestbook');
            }
                        
            // Display view
            View::factory('guestbook/views/backend/index')
                    ->assign('records', $records)
                    ->display();

        }

        /**
         * Form Component Save
         */
        public static function formComponentSave() {
            if (Request::post('guestbook_component_save')) {
                Option::update('guestbook_template', Request::post('guestbook_form_template'));
                Request::redirect('index.php?id=themes');
            }
        }


        /**
         * Form Component
         */
        public static function formComponent() {
            
            $_templates = Themes::getTemplates();
            foreach($_templates as $template) $templates[basename($template, '.template.php')] = basename($template, '.template.php');

            echo (
                Form::open().
                Form::label('guestbook_form_template', __('Guestbook Template', 'guestbook')).
                Form::select('guestbook_form_template', $templates, Option::get('guestbook_template')).
                Html::br().
                Form::submit('guestbook_component_save', __('Save', 'guestbook'), array('class' => 'btn')).        
                Form::close()
            );
        }

    }