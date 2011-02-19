<html>

<head>
<link href="<?php base_url()?>css/frontpagestyle.css" rel="stylesheet" type="text/css">
<title>VORFS - Fantasy Anything!</title>
</head>

<body>

	<div id="wrapper">
		<header>
			<div id="header">
				<img src="<?php base_url()?>images/logo_big.png" alt="VORFS LOGO">
				
				<div id="login_form">
					<form action="<?php base_url()?>index.php/login/validate_credentials" method="post">
					Username:<input type="text" name="username" autofocus="autofocus">
					Password:<input type="password" name="password">
					<input type="submit" name="submit" value="Login">
					<a href="<?php base_url()?>index.php/login/signup" class="button">Create Account</a>
					</form>
				</div>
				
				<div id="create">
					<!--<a href="<?php base_url()?>index.php/login/signup" class="button">Create Account</a>-->
				</div>
			</div>
		</header>
			
			<div id="container">
				<!--
				<div id="leftcol">
					<img src="<?php base_url()?>images/vorf_front2.png" alt="Placeholder Image">
				</div>
			
				<div id="rightcol">
						
					<br>					
							
					<h1>OWN</h1>
					<p>Musicians, wiffleball players, programmers--anybody!</p>
					
					<h1>SCORE</h1>
					<p>Score fantasy points!</p>
					
					<h1>COMPETE</h1>
					<p>Compete against your friends and the world!</p>
					<br>
					<p><a href="<?php base_url()?>index.php/login/signup" class="button">Create Account</a></p>

				</div>
				-->
				
				<img src="<?php base_url()?>images/OSC_new.png" alt="Placeholder Image">
			</div>
	</div>

<!--Works best in <a href="http://www.mozilla.com/en-US/firefox/beta/">Firefox 4</a>.-->

</body>

</html>
