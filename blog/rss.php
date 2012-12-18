<?xml version="1.0" encoding="utf-8"?>
<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', rtrim(dirname(__FILE__), '\\/'));
define('BACKEND', false);    
define('MONSTRA_ACCESS', true);

// Load bootstrap file
require_once(ROOT . DS . 'monstra' . DS . 'bootstrap.php');

// Get all posts for blog parent page/post
$posts = Pages::$pages->select('[parent="blog" and status="published"]', 5, 0, array('slug', 'title', 'author', 'date'), 'date', 'DESC');

// Date now
$now = date("D, d M Y H:i:s T");
header('Content-type: text/xml'); 
?>
<feed xml:lang="en-US" xmlns="http://www.w3.org/2005/Atom"> 
<title>MonstraCMS::BLOG::RSS</title>
<link><?php echo Option::get('siteurl'); ?></link>
<description>MonstraCMS::BLOG::RSS</description>
<language>en-us</language>
<pubDate><?php echo $now; ?></pubDate>
<lastBuildDate><?php echo $now; ?></lastBuildDate>
<generator>Monstra</generator>
<?php    
foreach ($posts as $post) {
echo'<entry>
<title>'.$post['title'].'</title>          
<link type=\'text/html\' href=\''.Option::get('siteurl').'blog/'.$post['slug'].'\' />
<summary>'.Text::cut(File::getContent(STORAGE . DS . 'pages' . DS . $post['id'] . '.page.txt'), 100).'</summary>
<author>'.$post['author'].'</author>
<pubDate>'.Date::format($post['date'], 'd M Y').'</pubDate>
</entry>';
}
?>    
</feed>