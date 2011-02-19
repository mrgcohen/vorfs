<!DOCTYPE html>

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>GSWL Pick 'Em</title>
	<link rel="stylesheet" href="<?php echo base_url();?>/css/style.css" type="text/css" media="screen" />
	
</head>

<h1>Create an Account!</h1>
<div id = "signup_form">

<fieldset>
<h3>Personal Information:</h3>

<?php
echo form_open('login/create_member');
echo 'First Name:';
echo form_input('first_name');
echo 'Last Name:';
echo form_input('last_name');
echo 'E-mail address:';
echo form_input('email_address');
?>
</fieldset>
</div>

<div id = "signup_form">
<fieldset>
<h3>Login Info:</h3>
<?php
echo 'Username:';
echo form_input('username');
echo 'Password:';
echo form_password('password');
echo 'Confirm Password:';
echo form_password('password2');
?>
<?php
echo form_submit('submit', 'Create Account');
echo form_close();
?>


<?php echo validation_errors('<p class="error">'); ?>
<?php 
	if ($error != 'none'){
		echo '<p class = "error">';
		echo $error;}	?>
</fieldset>
</div>