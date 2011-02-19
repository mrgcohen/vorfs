<!DOCTYPE html>
<?php $this->load->helper('html');?>
<?php $this->load->helper('url');?>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>VORFS - Fantasy Anything!</title>
	<link rel="stylesheet" href="<?php echo base_url();?>/css/style.css" type="text/css" media="screen">
	<?php 
		/*$this->load->helper('url');*/
		
		if($this->uri->segment(2)=="standings")
		{
			echo 
			('
				<script language="javascript" type="text/javascript">
				function setActive() 
				{
				  	aObj = document.getElementById(\'nav\').getElementsByTagName(\'a\');
				  	for(i=0;i<aObj.length;i++) 
				  	{ 
				    	if(document.location.href.indexOf(aObj[i].href)>=0) 
				    	{
				     	 	aObj[i].className=\'active\';
				    	}
				  	}
					aObjStand = document.getElementById(\'weekstandingsnav\').getElementsByTagName(\'a\');
				  	for(i=0;i<aObjStand.length;i++) 
				  	{ 
				    	if(document.location.href.indexOf(aObjStand[i].href)>=0) 
				    	{
				     	 	aObjStand[i].className=\'active\';
				    	}
				  	}
				}
				window.onload = setActive;
				</script>
			');
		}
		else if($this->uri->segment(2)=="team")
		{
			echo 
			('
				<script language="javascript" type="text/javascript">
				function setActive() 
				{
				  	aObj = document.getElementById(\'nav\').getElementsByTagName(\'a\');
				  	for(i=0;i<aObj.length;i++) 
				  	{ 
				    	if(document.location.href.indexOf(aObj[i].href)>=0) 
				    	{
				     	 	aObj[i].className=\'active\';
				    	}
				  	}
					aObjTeam = document.getElementById(\'weeknav\').getElementsByTagName(\'a\');
				  	for(i=0;i<aObjTeam.length;i++) 
				  	{ 
				    	if(document.location.href.indexOf(aObjTeam[i].href)>=0) 
				    	{
				     	 	aObjTeam[i].className=\'active\';
				    	}
				  	}
				}
				window.onload = setActive;
				</script>
			');
		}
		else
		{
			echo
			('
				<script language="javascript" type="text/javascript">
				function setActive() 
				{
				  	aObj = document.getElementById(\'nav\').getElementsByTagName(\'a\');
				  	for(i=0;i<aObj.length;i++) 
				  	{ 
				    	if(document.location.href.indexOf(aObj[i].href)>=0) 
				    	{
				     	 	aObj[i].className=\'active\';
				    	}
				  	}
				}
				window.onload = setActive;
				</script>
			');
		}

			?>
		<!--
		<script language="javascript" type="text/javascript">
			function setActive() 
			{
			  	aObj = document.getElementById('nav').getElementsByTagName('a');
			  	for(i=0;i<aObj.length;i++) 
			  	{ 
			    	if(document.location.href.indexOf(aObj[i].href)>=0) 
			    	{
			     	 	aObj[i].className='active';
			    	}
			  	}
			  	aObjTeam = document.getElementById('weeknav').getElementsByTagName('a');
			  	for(i=0;i<aObjTeam.length;i++) 
			  	{ 
			    	if(document.location.href.indexOf(aObjTeam[i].href)>=0) 
			    	{
			     	 	aObjTeam[i].className='active';
			    	}
			  	}
				aObjStand = document.getElementById('weekstandingsnav').getElementsByTagName('a');
			  	for(i=0;i<aObjStand.length;i++) 
			  	{ 
			    	if(document.location.href.indexOf(aObjStand[i].href)>=0) 
			    	{
			     	 	aObjStand[i].className='active';
			    	}
			  	}
			}
			window.onload = setActive;
		</script>
		-->
</head>
<body>
	<div id = "header"> 
		
		<div id="vorfslogo">
		<a href=<?php echo '"'.site_url(). '/site">'?>
		<img src=<?php echo '"'.base_url(). 'images/logo_vorfs.png" border=0 alt="vorfs_logo">'?>
		</a>
		</div>

		<div id = "headerpickem">
		<a href=<?php echo '"'.site_url(). '/site/home">'?>
		<img src=<?php echo '"'.base_url(). 'images/GSWL_logo_header.png" border=0 alt="gswl_logo">'?>
		</a>
		</div>
		
		
		
	<!--	
	<?//php echo img('images/logo5.png');?>
	
	<?//php echo img('images/logo_vorfs.png');?>

	<?//php echo img('images/logo3.png');?>


	
	<?//php echo img('images/GSWL_logo_header.png');?>
	-->
	
		<div id="headerlogout">
		<?php if ($this->session->userdata('username'))
		{
		echo 'Logged in as '. $this->session->userdata('username'). ' ';
		echo anchor('/login/logout', 'logout');
		}?>
		</div>
	</div>