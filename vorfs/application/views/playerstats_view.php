<!--
$stats_summed[0] contains the summed stats for your player.  
first_name
last_name
p_hits
etc
-->
<?php $this->load->view('/includes/links_bar'); ?>
<br>

<?php $this->load->helper('html');?>

<h1>

<?php echo $stats_summed[0]->first_name. ' '. $stats_summed[0]->last_name. ' - '. $stats_summed[0]->team_name;?>

</h1>


<br><br>


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


<div id = "player_pic">
	<?php $filename='images/'. $stats_summed[0]->player_id. '.jpg';
	
	if (file_exists($filename)) {
	    echo '<img src="'.base_url(). 'images/'. $stats_summed[0]->player_id. '.jpg" border=0 width = "115" height ="175">';
	} else {
	    echo '<img src="'.base_url(). 'images/no_picture.png" border=0 width = "115" height ="175">';
	}
	
	?>
	
	
	<?//php echo img('images/'. $stats_summed[0]->player_id. '.jpg');?>
	<?php echo img('images/'.$logo. '.png');?>
</div>

<div id = "playerstats_button">
<p><a href="<?php site_url()?>../../site/addplayer_confirm/<?php echo $stats_summed[0]->player_id?>" class="button" id="wooo">Add Player</a></p>
</div>


<div id = "hitting_stats_table">
<h3>Hitting Stats: </h3>
<table border = "1">
<thead>
	<th>Week</th><th>Opponent</th><th>AB</th><th>H</th><th>AVG</th><th>1B</th><th>2B</th><th>3B</th><th>HR</th><th>TB</th><th>RBI</th><th>R</th><th>BB</th><th>FP</th>
</thead>
<tbody>
	<?php foreach($stats as $row): ?>
	<tr><td><?= $row->game_date_code;?></td><td><?= $row->opp_nickname;?></td><td><?= $row->h_atbats;?></td><td><?= $row->h_hits;?></td><td></td><td><?= $row->h_1b;?></td><td><?= $row->h_2b;?></td><td><?= $row->h_3b;?></td><td><?= $row->h_hr;?></td><td><?= $row->h_tb;?></td><td><?= $row->h_rbi;?></td><td><?= $row->h_runs;?></td><td><?= $row->h_bb;?></td><td><?= $row->h_fp;?></td></tr>

	<?php endforeach;?>
	<tr><td></td><td>Total</td><td><?php echo $stats_summed[0]->h_atbats?></td><td><?php echo $stats_summed[0]->h_hits?></td><td><?php echo number_format($stats_summed[0]->h_avg, 3)?></td><td><?php echo $stats_summed[0]->h_1b?></td><td><?php echo $stats_summed[0]->h_2b?></td><td><?php echo $stats_summed[0]->h_3b?></td><td><?php echo $stats_summed[0]->h_hr?></td><td><?php echo $stats_summed[0]->h_rbi?></td><td><?php echo $stats_summed[0]->h_runs?></td><td><?php echo $stats_summed[0]->h_bb?></td><td><?php echo $stats_summed[0]->h_tb?></td><td><?php echo $stats_summed[0]->h_fp?></td></tr>
</tbody>
</table>
</div>



<div id = "pitching_stats_table">
<?php if ($stats_summed[0]->p_ip):?>
<h3>Pitching Stats: </h3>
	<table border = "1">
		<thead>
			<th>Week</th>
			<th>Opponent</th>
			<th>W</th>
			<th>L</th>
			<th>SV</th>
			<th>ERA</th>
			<th>WHIP</th>
			<th>IP</th>
			<th>ER</th>
			<th>H</th>
			<th>BB</th>
			<th>K</th>
			<th>FP</th>
		</thead>
	<tbody>
	<?php foreach($stats as $row): ?>
	
	<?php if($row->p_ip):?>
	<tr><td><?= $row->game_date_code;?></td><td><?= $row->opp_nickname;?></td><td><?= $row->p_wins;?></td><td><?= $row->p_losses;?></td><td><?= $row->p_saves;?></td><td></td><td></td><td><?php echo round($row->p_ip, 1);?></td><td><?= $row->p_runs;?></td><td><?= $row->p_hits;?></td><td><?= $row->p_bb;?></td><td><?= $row->p_k;?></td><td><?= round($row->p_fp, 1)?></td></tr>
	<?php endif;?>
	

	<?php endforeach;?>
<tr><td></td><td>Total</td><td><?php echo $stats_summed[0]->p_wins?></td><td><?php echo $stats_summed[0]->p_losses?></td><td><?php echo $stats_summed[0]->p_saves?></td><td><?php echo number_format( $stats_summed[0]->p_era, 2)?></td><td><?php echo number_format($stats_summed[0]->p_whip, 2)?></td><td><?php echo number_format($stats_summed[0]->p_ip, 1)?></td><td><?php echo $stats_summed[0]->p_runs?></td><td><?php echo $stats_summed[0]->p_hits?></td><td><?php echo $stats_summed[0]->p_bb?></td><td><?php echo $stats_summed[0]->p_k?></td><td><?php echo round($stats_summed[0]->p_fp);?></td>
</tbody>
</table>
	<?php endif;?>
	
<?php if (!$stats_summed[0]->p_ip):?>
	<h3>Pitching Stats: N/A</h3>
<?php endif;?>
</div>


<br><br>




