<?php

    // Main engine defines    
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT', rtrim(dirname(__FILE__), '\\/'));
    define('BACKEND', false);    
    define('MONSTRA_ACCESS', true);

    // Load bootstrap file
    require_once(ROOT . DS . 'monstra' . DS . 'bootstrap.php');

    // Get all pages for blog parent page
    $pages = Pages::$pages->select('[parent="blog" and status="published"]', 'all');
    $pages = Arr::subvalSort($pages, 'date', 'DESC');

    // Date now
    $now = date("D, d M Y H:i:s T");

    // Output
    $output = ' <?xml version="1.0"?>
                <rss version="2.0">
                <channel>
                    <title>MonstraCMS::BLOG::RSS</title>
                    <link>http://monstra.org</link>
                    <description>MonstraCMS::BLOG::RSS</description>
                    <language>en-us</language>
                    <pubDate>'.$now.'</pubDate>
                    <lastBuildDate>'.$now.'</lastBuildDate>
                    <docs></docs>
                    <managingEditor></managingEditor>
                    <webMaster></webMaster>';
    
    foreach ($pages as $page) {
        $output .= '<item>
                        <title>'.$page['title'].'</title>
                        <link>'.Option::get('siteurl').'blog/'.$page['slug'].'</link>            
                        <description>'.Text::cut(File::getContent(STORAGE . DS . 'pages' . DS . $page['id'] . '.page.txt'), 100).'</description>
                    </item>';
    }
    
    $output .= '</channel></rss>';

    // Output rss
    echo $output;