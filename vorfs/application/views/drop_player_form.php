<div id = "popup">

<h1> Drop Player </h1>

<br><br>

<h3> <?php echo $first_name. ' '. $last_name. ', '. $team_name;?> </h3> <br>

<h3> Week <?php echo $week;?> </h3>

<br><br>

<?php
echo form_open('site/dropplayer');


echo form_hidden('week', $this->uri->segment(4));
echo form_hidden('player_id', $this->uri->segment(3));

echo form_submit('drop', 'Drop');
echo form_close();
?>

</div>
