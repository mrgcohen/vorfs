<br>
<div id = "join_league">

	<h1>Fantasy Pro Wiffleball</h1>
		<img src=<?php echo '"'.base_url(). 'images/GSWL_logo_header.png" border=0 alt="gswl_logo">'?>


<div align="left">
	<font size="2">
		<br>
		<p><b>OWN</b> pro wiffleball players from across the country</p>
		<p><b>SCORE</b> hitting and pitching stats</p>
		<p><b>COMPETE</b> during the '11 season for prizes!</p>
		<br>
		Team Name:</div>
	</font>


<?php
echo form_open('login/create_team');
echo form_input('team_name');
echo form_submit('submit', 'Create Team');
echo form_close();
?>

</div>