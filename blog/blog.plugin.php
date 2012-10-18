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
        public static function getTags($slug = null) {

            // Display view
            return View::factory('blog/views/frontend/tags')
                    ->assign('tags', Blog::getTagsArray($slug))
                    ->render();

        }


        /**
         * Get tags array
         *
         *  <code> 
         *      echo Blog::getTagsArray();
         *  </code>
         *
         * @return array
         */
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

            // Get unique tags
            $tags = array_unique($tags);

            // Return tags
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
        public static function getPosts($nums = 2) {

            // Get page param
            $page = (Request::get('page')) ? (int)Request::get('page') : 1;

            if (Request::get('tag')) {
                $query = '[parent="'.Blog::$parent_page_name.'" and status="published" and contains(keywords, "'.Request::get('tag').'")]';
            } else {
                $query = '[parent="'.Blog::$parent_page_name.'" and status="published"]';
            }

            // Get Elements Count
            $elements = count(Pages::$pages->select($query, 'all'));

            // Get Pages Count
            $pages = ceil($elements/$nums);

 
            if ($page < 1) {
                $page = 1;
            } elseif ($page > $pages) {
                $page = $pages;
            }

            $start = ($page-1)*$nums;

            // If there is no posts
            if ($start < 0) $start = 0;

            // Get posts and sort by DESC
            $posts = Arr::subvalSort(Pages::$pages->select($query, $nums, $start), 'date', 'DESC');

            // 
            foreach($posts as $key => $post) {
                $post_short = explode("{cut}", Text::toHtml(File::getContent(STORAGE . DS . 'pages' . DS . $post['id'] . '.page.txt')));
                $posts[$key]['content'] = $post_short[0];
            }

            // Display view
            return View::factory('blog/views/frontend/index')
                    ->assign('posts', $posts)
                    ->render().
                   View::factory('blog/views/frontend/pager')
                    ->assign('pages', $pages)
                    ->assign('page', $page)
                    ->render();
        }


        /**
         * Get related posts
         *
         *  <code> 
         *      echo Blog::getRelatedPosts();
         *  </code>
         *
         * @return string
         */
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
         * Get post content
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


        /**
         * Get Blog Post title
         *
         *  <code> 
         *      echo Blog::getPostTitle();
         *  </code>
         *
         * @return string
         */
        public static function getPostTitle() {
            return Page::title();
        }

    }

