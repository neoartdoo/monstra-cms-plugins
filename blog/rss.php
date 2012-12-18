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
    $output = ' <?xml version="1.0" encoding="utf-8"?>
                <rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
                <channel>
                    <title>MonstraCMS::BLOG::RSS</title>
                    <link>'.Option::get('siteurl').'</link>
                    <atom:link href="'.Option::get('siteurl').'rss.php" rel="self" type="application/rss+xml"/>
                    <description>MonstraCMS::BLOG::RSS</description>
                    <language>en-us</language>
                    <pubDate>'.$now.'</pubDate>
                    <lastBuildDate>'.$now.'</lastBuildDate>
                    <generator>Monstra</generator>';
    
    foreach ($pages as $page) {
        $output .= '<item>
                        <title>'.$page['title'].'</title>
                        <link>'.Option::get('siteurl').'blog/'.$page['slug'].'</link>            
                        <description>'.Text::cut(File::getContent(STORAGE . DS . 'pages' . DS . $page['id'] . '.page.txt'), 100).'</description>
                        <author>'.$page['author'].'</author>
                        <pubDate>'.Date::format($page['date'], 'd M Y').'</pubDate>
                    </item>';
    }
    
    $output .= '</channel></rss>';

    // Output rss
    echo $output;