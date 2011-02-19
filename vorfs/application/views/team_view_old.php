<html>
<?php $this->load->view('/includes/links_bar');?>
<br>

<h1> <?php if(isset($records2)) : foreach($records2 as $row) : ?>

	<?php
	if ($username == $this->session->userdata('username'))
		echo 'My Team - '. $team_name;
	else
		echo $team_name. ' - '. $first_name. ' '. $last_name;
	?>
	
	<?php endforeach; ?>

	<?php else : ?>	
	<h2>No records were returned.</h2>
	<?php endif; ?>
</h1>



	<?php $this->load->view('/includes/week_bar', $username)?>
		
	<h3>
	<?php echo 'Total Score: '. $team_total. '  Overall rank: '. $place. ' of '. $num_teams;?>
	</h3>
	
		<div id = "team_info">
		<h4>
		Week <?php echo $this->uri->segment(4), ': '. $date;?>

		<br>
		<?php if (isset($team_total_fp)){echo 'Score: '.$team_total_fp. ' Weekly rank: '. $week_place. ' of '. $num_teams;}
		else {echo 'Score: N/A Weekly rank: N/A';}?>

		<br>
		<?php echo 'Active regions: ';
		if ($schedule['mass'] == 1){ echo 'MA ';}
		if ($schedule['new_york'] == 1){ echo 'NY ';}
		if ($schedule['phila'] == 1){ echo 'PA';}
		?>
		<br>
		<?php echo 'Roster Status: ';
			if ($roster_size>=5)
			{
				echo 'Complete (5/5)';
			}
			else 
			{
				echo '<div id="makethisred">Incomplete ('. $roster_size.'/5)</div>';
			}
			////This should light up in red or something if incomplete.
		?>
		</h4>

		<!--
		<?php 
		if ($username == $this->session->userdata('username')){//Only add player option if this is your team.
			echo anchor('site/players', 'Add player'). '<br>';
			echo anchor('site/settings', 'Change Team Name', 'id="wooo"'). '<br>';
			echo '<br><br>';
		}
		?>
		-->


		<?  if($username == $this->session->userdata('username')) { ?>
	        <p><a href="<?php site_url()?>../../players" class="button">Add Player</a>
	        <a href="<?php site_url()?>../../settings" class="button" id="wooo">Change Team Name</a></p>
	<?  } ?>

	</div>
	
	<div id="trashtalk_logo">
	<a href=<?php echo '"'.site_url(). '/site/trash_talk">'?>
		<img src=<?php echo '"'.base_url(). 'images/griffin.png" border=0 alt="trash_talk">'?>
	</a>
	</div>
	
	<div id = "trash_talk">

		<h5><?php echo $trash_talk;?></h5>
		<br>
		<!--<?php echo anchor('site/trash_talk', 'Talk Trash');?>-->
		
		<?php if ($username == $this->session->userdata('username')): ?>
			<p><a href="<?php site_url()?>../../trash_talk" class="button" id="wooo">Talk Trash!</a></p>
		<?php endif;?>
		<?php if ($username !== $this->session->userdata('username')): ?>
			<br><br><br>
		<?php endif;?>
			
	</div>
	
<br><br><br><br><br><br><br><br><br>

	<h3>My Roster: </h3>
		<?php //BEGIN FANCYBOX ?>
<?php $this->load->helper('url'); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>extras/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>extras/fancybox/jquery.easing-1.3.pack.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>extras/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />

<script type="text/javascript">
$(document).ready(function() {

	/* This is basic - uses default settings */
	
	$("a#inline").fancybox({
		'hideOnContentClick': true
	});

	$("a#wooo").fancybox();
	
});
</script>

<?php if(isset($team_roster[0]))
echo '<p>
<a href="#data" id="inline">Compare players</a>
<img src="'.base_url().'icons/32px-Crystal_Clear_mimetype_log.png">
</p>' ?>
<?php //END FANCYBOX ?>
	<div style="display:none"><div id="data">
	<?php //CODE FOR BAR GRAPH
	$player_counter = 0;
	$barinput = '';
	foreach($team_roster as $row) {
		$name = $row->lookup_last_name;
		$total_fp = $week_stats_sum[$player_counter][0]->h_fp + $week_stats_sum[$player_counter][0]->p_fp;
		$barinput = $barinput. $name. '='. round($total_fp). ','; //CHANGE IF SCORING SYSTEM CHANGES
		$player_counter++;
	}
	$barinput = $barinput. '@Title=Team Performance for Week '. $this->uri->segment(4);
	include("bargraph.php");
	//END CODE FOR BAR GRAPH ?></div></div>
	<?php 
	
	$table_parameters = "
	<div id = 'team_player_table'>
		<table border = '1'>
			<thead>
				<th>Opponent</th>
				<th>AB</th>
				<th>H</th>
				<th>1B</th>
				<th>2B</th>
				<th>3B</th>
				<th>HR</th>
				<th>TB</th>
				<th>RBI</th>
				<th>R</th>
				<th>BB</th>
				<th>FP (Hitting)</th>
				<th>W</th>
				<th>L</th>
				<th>SV</th>
				<th>IP</th>
				<th>ER</th>
				<th>H</th>
				<th>BB</th>
				<th>K</th>
				<th>FP (Pitching)</th>
				<th>Total FP</th>
			</thead>
			<tbody>";
	

			
	$player_counter = 0;
	 foreach ($team_roster as $row){
		//if ($row->active == 1){echo "Active ";}
		//else {echo "Alternate ";}
		
		echo '<div id ="player">';
		
		$filename='images/'. $row->player. '.jpg';
		if (file_exists($filename)) {
		   echo '<img src="'.base_url(). 'images/'. $row->player. '.jpg" border=0 width = "80" height ="121">';
		} else {
		    echo '<img src="'.base_url(). 'images/no_picture.png" border=0 width = "80" height ="121">';
		}
		
		
		echo '</div>';
		
		echo '<div id ="player_on_team">';
			echo '<div id ="playername_on_team">';
			echo anchor('site/player_stats/'. $row->player, $row->lookup_first_name. ' '. $row->lookup_last_name);
			echo '</div>';
			echo ' ';
		echo $row->team_name. ' ';
		echo 'Points: ';
		$total_fp = $week_stats_sum[$player_counter][0]->h_fp + $week_stats_sum[$player_counter][0]->p_fp;
		echo $total_fp. ' ';
		if ($username == $this->session->userdata('username')){//Only drop option if this is your team.
			echo anchor('site/dropplayer_confirm/'.$row->player.'/'.$this->uri->segment(4), 'Drop', 'id="wooo"');
		}
		echo '</div>';
		

		
		echo $table_parameters;
			for ($x=0; $x<count($week_stats[$player_counter]); $x++){
				echo '<tr><td>'. $week_stats[$player_counter][$x]->opp_nickname. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->h_atbats. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->h_hits. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->h_1b. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->h_2b. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->h_3b. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->h_hr. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->h_tb. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->h_rbi. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->h_runs. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->h_bb. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->h_fp. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->p_wins. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->p_losses. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->p_saves. '</td>';
				
				if (is_integer($week_stats[$player_counter][$x]->p_ip) == false){
					$temp=$week_stats[$player_counter][$x]->p_ip;
					while ($temp > 1){
						$temp--;
					}
					if ($temp > 0.3 and $temp < 0.35){//this is supposed to say  = 0.333 but that didn't work
						$temp=$week_stats[$player_counter][$x]->p_ip=($week_stats[$player_counter][$x]->p_ip - (1/3)) + (0.1);
					}
					else if ($temp > 0.65 and $temp < 0.7){//same, 0.6666
						$temp=$week_stats[$player_counter][$x]->p_ip=($temp=$week_stats[$player_counter][$x]->p_ip - (2/3)) + (0.2);
					}
				}
				if ($week_stats[$player_counter][$x]->p_ip){
				echo '<td>'. number_format($week_stats[$player_counter][$x]->p_ip, 1). '</td>';}
				else {echo '<td></td>';}
				

				echo '<td>'. $week_stats[$player_counter][$x]->p_runs. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->p_hits. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->p_bb. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->p_k. '</td>';
				echo '<td>'. $week_stats[$player_counter][$x]->p_fp. '</td>';
				$temp = $week_stats[$player_counter][$x]->h_fp + $week_stats[$player_counter][$x]->p_fp;
				echo '<td>'. $temp. '</td>';
			}
		
		$player_counter++;
		echo "</tbody></table></div>";
		
		if (count($week_stats[$player_counter-1]) ==0 ){echo '<br><br><br><br>';}
		else if (count($week_stats[$player_counter-1]) ==1 ){echo '<br><br><br>';}
		else if (count($week_stats[$player_counter-1]) ==2 ){echo '<br><br>';}
		else if (count($week_stats[$player_counter-1]) ==3 ){echo '<br>';}

	}
	?>

	
</body>
</html>
