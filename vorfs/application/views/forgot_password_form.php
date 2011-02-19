<h1>Forgot Password?</h1>
<fieldset>

<?php
   
echo form_open('login/forgot_sent');

echo form_input('email_address', set_value('email_address', 'Email Address'));

echo form_submit('submit', 'Send e-mail');
?>

<?php echo validation_errors('<p class="error">'); ?>
</fieldset>
