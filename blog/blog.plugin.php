<?php

    /**
     *  Blog plugin
     *
     *  @package Monstra
     *  @subpackage Plugins
     *  @author Romanenko Sergey / Awilum
     *  @copyright 2012 Romanenko Sergey / Awilum
     *  @version 1.4.0
     *
     */


    // Register plugin
    Plugin::register( __FILE__,                    
                    __('Blog', 'blog'),
                    __('Blog plugin for Monstra', 'blog'),  
                    '1.4.0',
                    'Awilum',                 
                    'http://monstra.org/');


    /**
     * Blog Class
     */
    class Blog {


        /**
         * Parrent page name(slug)
         *
         * @var string
         */
        public static $parent_page_name = 'blog';


        /**
         * Get tags
         *
         *  <code> 
         *      echo Blog::getTags();
         *  </code>
         *
         * @return string
         */
        public static function getTags() {

            // Display view
            return View::factory('blog/views/frontend/tags')
                    ->assign('tags', Blog::getTagsArray())
                    ->render();

        }



        public static function getTagsArray($slug = null) {

            $tags = array();
            $tags_string = '';

            if ($slug == null) {
                $posts = Pages::$pages->select('[parent="'.Blog::$parent_page_name.'" and status="published"]', 'all');
            } else {
                $posts = Pages::$pages->select('[parent="'.Blog::$parent_page_name.'" and status="published" and slug="'.$slug.'"]', 'all');
            }
        
            foreach($posts as $post) {
                $tags_string .= $post['keywords'].',';
            }

            $tags_string = substr($tags_string, 0, strlen($tags_string)-1);   

            // Explode tags in tags array
            $tags = explode(',', $tags_string);   

            // Remove empty array elementss
            foreach ($tags as $key => $value) {
                if ($tags[$key] == '') {
                    unset($tags[$key]);
                }
            }

            // Trim tags
            array_walk($tags, create_function('&$val', '$val = trim($val);')); 

            $tags = array_unique($tags);

            return $tags;
        }


        /**
         * Get posts
         *
         *  <code> 
         *      // Get all posts
         *      echo Blog::getPosts();
         *
         *      // Get last 5 posts
         *      echo Blog::getPosts(5);
         *  </code>
         *
         * @return string
         */
        public static function getPosts($limit = null) {

            if (Request::get('tag')) {
                $query = '[parent="'.Blog::$parent_page_name.'" and status="published" and contains(keywords, "'.Request::get('tag').'")]';
            } else {
                $query = '[parent="'.Blog::$parent_page_name.'" and status="published"]';
            }


            $posts = Arr::subvalSort(Pages::$pages->select($query, ($limit == null) ? 'all' : (int)$limit), 'date', 'DESC');

            foreach($posts as $key => $post) {
                $post_short = explode("{cut}", Text::toHtml(File::getContent(STORAGE . DS . 'pages' . DS . $post['id'] . '.page.txt')));
                $posts[$key]['content'] = $post_short[0];
            }           

            // Display view
            return View::factory('blog/views/frontend/index')
                    ->assign('posts', $posts)
                    ->render();
        }


        public static function getRelatedPosts($limit = null) {

            $related_posts = array();
            $tags = Blog::getTagsArray(Page::slug());

            foreach($tags as $tag) {

                $query = '[parent="'.Blog::$parent_page_name.'" and status="published" and contains(keywords, "'.$tag.'") and slug!="'.Page::slug().'"]';
                
                if ($result = Arr::subvalSort(Pages::$pages->select($query, ($limit == null) ? 'all' : (int)$limit), 'date', 'DESC')) {
                    $related_posts = $result; 
                }
            }

            // Display view
            return View::factory('blog/views/frontend/related_posts')
                    ->assign('related_posts', $related_posts)
                    ->render();

        }


        /**
         * Get post
         *
         *  <code> 
         *      echo Blog::getPost();
         *  </code>
         *
         * @return string
         */
        public static function getPost() {

            // Get post
            $post = Text::toHtml(File::getContent(STORAGE . DS . 'pages' . DS . Pages::$page['id'] . '.page.txt'));                

            // Apply content filter
            $post = Filter::apply('content', $post);

            // Remove {cut} shortcode
            $post = strtr($post, array('{cut}' => ''));

            // Return post
            return $post;
        }

    }

