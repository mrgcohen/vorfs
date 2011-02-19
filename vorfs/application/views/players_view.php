<?php $this->load->view('/includes/links_bar');?>

<br>

<?php //BEGIN FANCYBOX ?>
<?php $this->load->helper('url'); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>extras/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>extras/fancybox/jquery.easing-1.3.pack.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>extras/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />

<script type="text/javascript">
$(document).ready(function() {

	/* This is basic - uses default settings */

	$("a#wooo").fancybox();
	
});
</script>

<?php if(isset($team_roster[0]))
echo '<p>
<a href="#data" id="inline">Compare players</a>
<img src="'.base_url().'icons/32px-Crystal_Clear_mimetype_log.png">
</p>' ?>
<?php //END FANCYBOX ?>

<h1>Players</h1>

<div id="players_top">
	<?php if (isset($error)){echo $error. '<br><br>';}?>
	<?php

	echo '<div id = "lastname_text">';
	echo 'Last name: ';
	echo '</div>';
	
	echo '<div id = "player_search_lastname">';
	echo form_open('site/players');
	echo form_input('player', '');
	echo '</div>';
	
	echo '<div id = "player_search_region">';
	echo 'Region: ';
	echo form_dropdown('region', $region, 'null');
	echo '</div>';
	
	echo '<div id = "player_search_team">';
	echo 'Team: ';
	echo form_dropdown('team', $team, 'null');
	echo '<br>';
	echo '<br>';
	echo '</div>';	
	
	echo '<div id = "player_search">';
	echo form_submit('submit', 'Search');
	echo form_close();
	echo '</div>';
	
	echo '<div id = "display_all">';
	echo form_open('site/players');
	echo form_hidden('reset', 'reset');
	echo form_submit('submit', 'Display All');
	echo form_close();
	echo '</div>';
	
	?>
<br>

	<?php if( count($allstats['stuff']) ==0 ){ echo 'No players found'; die();}?>
	
</div>	

<br>

<div id = "players_table">
	<table>
		<thead>
			<?php foreach ($fields as $field_name  => $field_display): ?>
				<th>
					<?php echo anchor("site/players/$field_name/". 
					(($field_display == 'Player' OR $field_display == 'Team' OR $field_display == 'ERA' OR $field_display == 'WHIP' OR $field_display == 'ER' OR $field_display == 'L' OR $field_display == ' H ' OR $field_display == ' BB ') ? 'asc' : 'desc'), //Team sorts ascending, all others descending.  Pitching will be ascending for some. 
					$field_display); ?>
				</th>
			<?php endforeach; ?>
		</thead>
		<tbody>
		<tr>
		<?php for ($x=0; $x<count($allstats['stuff']); $x++): ?>
		<td><?php echo anchor('site/player_stats/'. $allstats['stuff'][$x]->player_id, $allstats['stuff'][$x]->first_name. ' '. $allstats['stuff'][$x]->last_name); ?></td>
		<td><?php echo $allstats['stuff'][$x]->team_name;?>
		<td><?php echo $allstats['stuff'][$x]->h_atbats; ?></td>
		<td><?php echo $allstats['stuff'][$x]->h_hits; ?></td>
		<td><?php echo number_format($allstats['stuff'][$x]->h_avg, 3); ?></td>
		<td><?php echo $allstats['stuff'][$x]->h_1b; ?></td>
		<td><?php echo $allstats['stuff'][$x]->h_2b; ?></td>
		<td><?php echo $allstats['stuff'][$x]->h_3b; ?></td>
		<td><?php echo $allstats['stuff'][$x]->h_hr; ?></td>
		<td><?php echo $allstats['stuff'][$x]->h_tb; ?></td>
		<td><?php echo $allstats['stuff'][$x]->h_rbi; ?></td>
		<td><?php echo $allstats['stuff'][$x]->h_runs; ?></td>
		<td><?php echo $allstats['stuff'][$x]->h_bb; ?></td>
		<td><?php echo $allstats['stuff'][$x]->h_fp; ?></td>
		
		<td><?php 
		if ($allstats['stuff'][$x]->p_era == 999){echo '';}
		else {echo $allstats['stuff'][$x]->p_wins;} ?></td>
		
		
		<td><?php
		if ($allstats['stuff'][$x]->p_runs == 999){echo '';}
		else{ echo $allstats['stuff'][$x]->p_losses;} ?></td>
		
		<td><?php
		if ($allstats['stuff'][$x]->p_era == 999){echo '';}
		else {echo $allstats['stuff'][$x]->p_saves;} ?></td>
		
		<td><?php 
		if ($allstats['stuff'][$x]->p_era == 999){echo '';}
		else if ($allstats['stuff'][$x]->p_era){
			echo number_format($allstats['stuff'][$x]->p_era, 2);}
		else if ($allstats['stuff'][$x]->p_ip !=0){
		echo "0.00";}//This is the 0.00 Steffy case?></td>
	
		<td><?php 
		if ($allstats['stuff'][$x]->p_whip == 999){
			echo '';}
		else if ($allstats['stuff'][$x]->p_whip){
		echo number_format($allstats['stuff'][$x]->p_whip, 2);}
		else if ($allstats['stuff'][$x]->p_whip !=0){
		echo "0.00";}//Same thing in case someone has a 0.00 WHIP ?></td>
		
		<td><?php if ($allstats['stuff'][$x]->p_ip != 0)
		echo number_format($allstats['stuff'][$x]->p_ip, 1); ?></td>
		
		<td><?php 
		if ($allstats['stuff'][$x]->p_runs == 999){echo '';}
		else{echo $allstats['stuff'][$x]->p_runs;} ?></td>
			
		<td><?php 
		if ($allstats['stuff'][$x]->p_hits == 999){echo '';}
		else {echo $allstats['stuff'][$x]->p_hits;} ?></td>
		
		<td><?php 
		if ($allstats['stuff'][$x]->p_bb == 999){echo '';}
		else {echo $allstats['stuff'][$x]->p_bb;} ?></td>
		
		<td><?php 
		if ($allstats['stuff'][$x]->p_era == 999){echo '';}
		else {echo $allstats['stuff'][$x]->p_k;} ?></td>
		
		<td><?php echo $allstats['stuff'][$x]->p_fp; ?></td>
		<td><?php echo $allstats['stuff'][$x]->total_fp; ?></td>
		<td><?php
			if ($allstats['stuff'][$x]->owned == 1){ echo 'X';}
			else {echo anchor('/site/addplayer_confirm/'. $allstats['stuff'][$x]->player_id, 'add','id="wooo"');}
		?></td>
		</tr>
		<?php endfor?>
	</table>
</div>
<?php if (strlen($pagination)): ?> 
	<div id = "pagination">
		<br>
	
	<?php if (count($allstats['stuff']) < $limit){echo count($allstats['stuff']). ' players.<br>';}
	else {echo $num_players. ' players.<br>';}?>
	
	<?php if (count($allstats['stuff'])>=20){echo 'Pages: '.$pagination;} ?>
	
	</div>
<?php endif; ?>



