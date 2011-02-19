<html>

	<?php $this->load->view('/includes/links_bar');?>
<br>
	<h1>GSWL Pick 'Em: Fantasy Pro Wiffleball</h1>
	<br>
	
	<div id = "home_view">
		<br><br>
	
	<div id = "home_wifflers">
		<?php echo anchor('site/players', 'Top wifflers:')?>
		<table>
			<?php 
			$x=1;
			if (isset($players)){
				foreach ($players as $row){
					echo '<tr><td>'.$x. '.  '. '</td>';
					echo '<td>'. anchor('site/player_stats/'.$row->player_id, $row->first_name. ' '. $row->last_name). '</td>';
					echo '<td>'. $row->team_name. '</td>';
					echo '<td>'. $row->total_fp. '</td></tr>';
					$x++;
				}
			}
			else{//no players
				echo '<tr><td>No players listed.</td></tr>';
			}				
			?>
		</table>
	</div>
	
	
	<!--rules go here-->
	
	
	<div id = "home_leaders">
		<?php echo anchor('site/standings/0', 'Leaders:')?>
		<table>
			<?php 
			$x=1;
			if (isset($standings['records'])){
				foreach ($standings['records'] as $row){
					if ($x<=10){
					echo '<tr><td>'. $row['place']. '.  '. '</td>';
					echo '<td>'. anchor('site/team/'.$row['username'], $row['team_name']). '</td>';
					echo '<td>'. $row['first_name']. ' '. $row['last_name'].'</td>';
					echo '<td>'.$row['total_fp'].'</td></tr>';
					}
					$x++;
				}
			}
			
			else{
				echo '<tr><td>No teams registered.</td></tr>';
			}	
			?>
		</table>
	</div>
</div>

<?php 
	if ($commish == 1){
		echo '<br><br><br>';
		echo anchor('/site/commish', 'Commissioner View');
	}
?>
</body>
</html>


