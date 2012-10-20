<h2><?php echo  __('Guestbook', 'guestbook'); ?></h2>
<br />
<?php foreach($records as $record) { ?>
    <h4><?php echo Html::toText($record['username']); ?> <small> - <?php echo Date::format($record['date']); ?></small></h4>
    <br />
    <?php echo Html::toText($record['message']); ?>
    <hr>
<?php } ?>
