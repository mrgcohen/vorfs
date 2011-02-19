<?php //BEGIN FANCYBOX ?>
<?php $this->load->helper('url'); ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>extras/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>extras/fancybox/jquery.easing-1.3.pack.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>extras/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />

<script type="text/javascript">
$(document).ready(function() {

	/* This is basic - uses default settings */
	
	$("a#nounder").fancybox();

	$("a#wooo").fancybox();
	
});
</script>

<?php if(isset($team_roster[0]))
echo '<p>
<a href="#data" id="inline">Compare players</a>
<img src="'.base_url().'icons/32px-Crystal_Clear_mimetype_log.png">
</p>' ?>
<?php //END FANCYBOX ?>

<?php $this->load->view('/includes/links_bar');?>
<br>

<h1>Standings</h1>

<!--
<?php echo anchor('site/standings', 'Overall'). ' Week: ';?>
<?php
	for ($x=1; $x<=8; $x++){
		echo anchor('site/standings/'.$x, $x). ' ';
	}
?>
-->

<?php $this->load->view('/includes/week_bar_standings');?>

<style type="text/css">
</style>

<br>
<div id ="standings">
	<h2>Standings:</h2>
	<?php if(!$this->uri->segment(3)) echo '<h5>Click a team\'s score to graph your performance versus theirs!</h5>'; ?>
	<table id="standingstable">		
			<thead>
				<th>Rank</th>
				<th>Team</th>
				<th>Owner</th>
				<th>Score</th>
			</thead>

		<tbody>
			<?php if(isset($records)) : foreach($records as $row) : ?>
			
			<tr>
				<td><?php echo $row['place']?></td>
				<td><?php echo anchor('site/team/'.$row['username'], $row['team_name']) ?></td>
				<td><?php echo $row['first_name']. ' '. $row['last_name'] ?></td>
				<td><div id="better"></div><?php if(!$this->uri->segment(3)) {
				echo anchor('site/graph/'. $this->session->userdata('username'). '/team_total_fp/'. $row['username']. '/'. $row['team_name'],$row['total_fp'], 'id="nounder"');?></td>
				<?php } else echo $row['total_fp']; ?></div>
			</tr>
			
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else : ?>	
<h2>No records were returned.</h2>
<?php endif; ?>
</div>
<br>
<h3>Expanded Standings:</h3>
<?php if(!$this->uri->segment(3)) echo '<h5>Click a team\'s stat to graph your performance versus theirs!</h5>' ?>
<div id ="expanded_standings">
	<table id ="expanded_standingstable">		
		<thead>
			<th>Rank</th>
			<th>Team</th>
			<th>Owner</th>
			<th>AB</th>
			<th>H</th>
			<th>AVG</th>
			<th>TB</th>
			<th>RBI</th>
			<th>R</th>
			<th>Hitting FP</th>
			<th></th>
			<th>W</th>
			<th>SV</th>
			<th>ERA</th>
			<th>IP</th>
			<th>ER</th>
			<th>K</th>
			<th>Pitching FP</th>
			<th>Total FP</th>
		</thead>
		<tbody>
			<?php if(isset($records)) : foreach($records as $row) : ?>
			<?php if($this->uri->segment(3)) { ?>
			<tr>
				<td><?php echo $row['place'];?></td>
				<td><?php echo anchor('site/team/'.$row['username'], $row['team_name']);?></td>
				<td><?php echo $row['first_name']. ' '. $row['last_name'];?></td>
				
				<td><?php echo $row['h_atbats'];?></td>
				<td><?php echo $row['h_hits'];?></td>
				<td><?php if ($row['h_atbats']){echo number_format($row['h_hits']/$row['h_atbats'], 3);}?></td>
				
				<td><?php echo $row['h_tb'];?></td>
				<td><?php echo $row['h_rbi'];?></td>
				<td><?php echo $row['h_runs'];?></td>
				<td><?php echo $row['h_fp'];?></td>
				<td></td>
				<td><?php echo $row['p_wins'];?></td>
				<td><?php echo $row['p_saves'];?></td>
				
				<td><?php if ($row['p_ip']){echo number_format($row['p_runs']/($row['p_ip']/4),2);}?>
				<td><?php echo $row['p_ip'];?></td>
				<td><?php echo $row['p_runs'];?></td>
				<td><?php echo $row['p_k'];?></td>
				<td><?php echo $row['p_fp'];?></td>
				<td><?php echo $row['total_fp'];?></td>
			</tr>
			<?php } else { ?>
			<tr>
				<td><?php echo $row['place'];?></td>
				<td><?php echo anchor('site/team/'.$row['username'], $row['team_name']);?></td>
				<td><?php echo $row['first_name']. ' '. $row['last_name'];?></td>
				
				<td><?php echo anchor('site/graph/'. $this->session->userdata('username'). '/h_atbats/'. $row['username']. '/'. $row['team_name'],$row['h_atbats'], 'id="nounder"');?></td>
				<td><?php echo anchor('site/graph/'. $this->session->userdata('username'). '/h_hits/'. $row['username']. '/'. $row['team_name'],$row['h_hits'], 'id="nounder"');?></td>
				<td><?php if ($row['h_atbats']){echo number_format($row['h_hits']/$row['h_atbats'], 3);}?></td>
				
				<td><?php echo anchor('site/graph/'. $this->session->userdata('username'). '/h_tb/'. $row['username']. '/'. $row['team_name'],$row['h_tb'], 'id="nounder"');?></td>
				<td><?php echo anchor('site/graph/'. $this->session->userdata('username'). '/h_rbi/'. $row['username']. '/'. $row['team_name'],$row['h_rbi'], 'id="nounder"');?></td>
				<td><?php echo anchor('site/graph/'. $this->session->userdata('username'). '/h_runs/'. $row['username']. '/'. $row['team_name'],$row['h_runs'], 'id="nounder"');?></td>
				<td><?php echo anchor('site/graph/'. $this->session->userdata('username'). '/h_fp/'. $row['username']. '/'. $row['team_name'],$row['h_fp'], 'id="nounder"');?></td>
				<td></td>
				<td><?php echo anchor('site/graph/'. $this->session->userdata('username'). '/p_wins/'. $row['username']. '/'. $row['team_name'],$row['p_wins'], 'id="nounder"');?></td>
				<td><?php echo anchor('site/graph/'. $this->session->userdata('username'). '/p_saves/'. $row['username']. '/'. $row['team_name'],$row['p_saves'], 'id="nounder"');?></td>
				
				<td><?php if ($row['p_ip']){echo number_format($row['p_runs']/($row['p_ip']/4),2);}?>
				<td><?php echo anchor('site/graph/'. $this->session->userdata('username'). '/p_ip/'. $row['username']. '/'. $row['team_name'],$row['p_ip'], 'id="nounder"');?></td>
				<td><?php echo anchor('site/graph/'. $this->session->userdata('username'). '/p_runs/'. $row['username']. '/'. $row['team_name'],$row['p_runs'], 'id="nounder"');?></td>
				<td><?php echo anchor('site/graph/'. $this->session->userdata('username'). '/p_k/'. $row['username']. '/'. $row['team_name'],$row['p_k'], 'id="nounder"');?></td>
				<td><?php echo anchor('site/graph/'. $this->session->userdata('username'). '/p_fp/'. $row['username']. '/'. $row['team_name'],$row['p_fp'], 'id="nounder"');?></td>
				<td><?php echo anchor('site/graph/'. $this->session->userdata('username'). '/team_total_fp/'. $row['username']. '/'. $row['team_name'],$row['total_fp'], 'id="nounder"');?></td>
			</tr>
			<?php } endforeach; ?>
		</tbody>
	</table>
<?php else : ?>	
<h2>No records were returned.</h2>
<?php endif; ?>
</div>


