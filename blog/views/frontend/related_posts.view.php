<div>
<?php foreach($related_posts as $related_post) { ?>
<a href="<?php echo Option::get('siteurl'); ?>blog/<?php echo $related_post['slug']; ?>"><?php echo $related_post['title']; ?></a><br>
<?php } ?>
</div>