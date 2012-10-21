<br /><h3><?php echo __('Add comment', 'guestbook'); ?></h3><br/>
<form method="post">
    <?php echo (Form::hidden('csrf', Security::token())); ?>
    <label><?php echo __('Username', 'guestbook'); ?></label>
    <input type="text" name="guestbook_username" class="input-xlarge" value="<?php echo $username; ?>" /><br />
    <label><?php echo __('Email', 'guestbook'); ?></label>
    <input type="text" name="guestbook_email" class="input-xlarge" value="<?php echo $email; ?>" /><br />
    <label><?php echo __('Message', 'guestbook'); ?></label>
    <textarea class="input-xxlarge" rows="10" name="guestbook_message"><?php echo $message; ?></textarea><br /><br />
    
    <?php if (Option::get('captcha_installed') == 'true') { ?>
    <label><?php echo __('Captcha', 'users'); ?><label>
    <input type="text" name="answer"><?php if (isset($errors['captcha_wrong'])) echo Html::nbsp(3).'<span class="error">'.$errors['captcha_wrong'].'</span>'; ?>
    <?php CryptCaptcha::draw(); ?>
    <?php } ?>

    <?php if (count($errors) > 0) { ?>
    <br>
    <ul>
    <?php foreach($errors as $error) { ?>
        <li><?php echo $error; ?></li>
    <?php } ?>             
    </ul>             
    <?php } ?> 
    <br>
    <input type="submit" value="<?php echo __('Send', 'guestbook'); ?>" name="guestbook_submit"/>
</form>