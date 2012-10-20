<br /><h3><?php echo __('Add comment', 'guestbook'); ?></h3><br/>
<form method="post">
    <label><?php echo __('Username', 'guestbook'); ?></label><br />
    <input type="text" name="guestbook_username" size="40" value="<?php echo $username; ?>" /><br />
    <label><?php echo __('Email', 'guestbook'); ?></label><br />
    <input type="text" name="guestbook_email" size="40" value="<?php echo $email; ?>" /><br />
    <label><?php echo __('Message', 'guestbook'); ?></label><br />
    <textarea rows="10" cols="100" name="guestbook_message"><?php echo $message; ?></textarea><br /><br />
    <?php echo Captcha::getMathQuestion(); ?>
    <input type="text" size="10" name="answer" />
    <?php if (count($errors) > 0) { ?>
    <ul>
    <?php foreach($errors as $error) { ?>
        <li><?php echo $error; ?></li>
    <?php } ?>             
    </ul>             
    <?php } ?> 
    <br /><br />
    <input type="submit" value="<?php echo __('Send', 'guestbook'); ?>" name="guestbook_submit"/>
</form>