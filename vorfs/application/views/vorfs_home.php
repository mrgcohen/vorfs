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

<h1>Vorfs Home</h1>


<div id = "vorf">
<a href=<?php echo '"'.site_url(). '/site/join">'?>
<img src=<?php echo '"'.base_url(). 'images/join_vorf.png" border=0 alt="join_vorf">'?>
</a>
</div>

<div id = "vorf">
<a href=<?php echo '"'.site_url(). '/site/create", id="wooo">'?>
<img src=<?php echo '"'.base_url(). 'images/create_vorf.png" border=0 alt="create_vorf">'?>
</a>
</div>

<div id = "my_vorfs">
<h2>My Vorfs:</h2>
<?php foreach($my_teams as $row):?>
	
	<!--
	<a href=<?php echo '"'.site_url(). '/site/home">'?>	
	<img src=<?php echo '"'.base_url(). 'images/GSWL_logo_header.png" border=0 alt="gswl_logo">'?>
	</a>
	-->
	<?php echo '<h4>' ?>
	<?php echo anchor('site/home', $row->league_description)?>
	<?php echo ' | ' ?>
	<?php echo anchor('site/team/'. $username. '/1', $row->team_name);?>
	<?php if ($row->commish == $username){echo '*';}?>
	<?php echo '</h4>' ?>
	<br><br><br>

<?php endforeach;?>
</div>