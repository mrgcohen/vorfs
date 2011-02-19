<div id ="popup">
<? //php $this->load->view('/includes/links_bar');?>
<br>

<h1>Change Team Name</h1>



<br><br>

<?php	
echo form_open('site/update');
echo 'Enter new team name below and then press submit:';
echo form_input('new_name', 'new team name');
echo form_submit('submit', 'Submit');
echo form_close();
?>

<?/*php
echo form_open('site/update_password');
echo form_input('current_password', 'current password');
echo form_input('new_password', 'new password');
echo form_input('new_password2', 'confirm new password');
echo form_submit('submit', 'change password');
echo form_close();
*/?>

</div>
