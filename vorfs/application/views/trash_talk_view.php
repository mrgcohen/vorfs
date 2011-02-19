<div id ="popup">
<h1>Talk Trash</h1>

<?php //$this->load->view('/includes/links_bar');?>

<br><br>

<?php
echo form_open('site/update_trash_talk');
echo form_input('trash_message', $trash_current);
echo form_submit('submit', 'Talk Trash!');
echo form_close();
?>
<br>
140 chars will be displayed
</div>
