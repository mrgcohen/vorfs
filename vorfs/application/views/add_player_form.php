<div id ="popup">
<h1> Add Player </h1>

<br><br>

<h3> <?php echo $first_name. ' '. $last_name. ', '. $team_name;?> </h3>

<br><br>

<?php
	if (isset($region)){
		echo form_open('site/addplayer');

		$options = $region;
		echo 'Week: ';
		echo form_dropdown('week', $options, '1');
		echo form_hidden('player_id', $this->uri->segment(3));

		echo form_submit('add', 'Add');
		echo form_close();
	}
	
	else{
		echo $error. ' ';
		echo anchor('site/players', 'Back');
	}
?>

</div>