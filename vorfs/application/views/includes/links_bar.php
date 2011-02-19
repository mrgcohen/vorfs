<div id = "links_bar">

<ul id="nav">

	<li>
	<?php echo anchor('site/home', 'League'). ' ' ?>
	</li>
	
	<li>
	<?php echo anchor('site/team/'. $this->session->userdata('username'), 'My Team'). ' ' ?>
	</li>
	
	<li>
	<?php echo anchor('/site/players', 'Players'). ' ' ?>
	</li>
	
	<li>
	<?php echo anchor('/site/standings/', 'Standings'). ' ' ?>
	</li>
	
	<li>
	<?php echo anchor('/site/schedule', 'Schedule'). ' ' ?>
	</li>
	
	<li>
	<?php echo anchor('/site/rules', 'Rules'). ' ' ?>
	</li>
	
	<li>
	<a href = "http://www.goldenstickwiffle.com" target="_blank">GSWL Home</a>
	</li>
	
	
</ul>

</div>
