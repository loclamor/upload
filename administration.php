<?php
require_once 'conf/init.php';

$admin = new Site_Administration();
if(!isset($_GET['noDisplay'])){ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title><?php echo $admin->getTitle();?></title>
		<link rel="stylesheet" media="screen" type="text/css" title="style" href="style/style.css" />
		<link rel="stylesheet" media="screen" type="text/css" title="style JQ ui" href="css/smoothness/jquery-ui-1.8.18.custom.css" />
		<script src="js/jquery-1.7.1.min.js" type="text/javascript" language="javascript" ></script>
		<script src="js/jquery.anythingslider.js" type="text/javascript" language="javascript" ></script>
		<script src="js/jquery-ui-1.8.13.custom.min.js" type="text/javascript" language="javascript" ></script>
		<script src="js/functions.js" type="text/javascript" language="javascript" ></script>
		
	</head>
	<body>
		<div id="head" style="background-color: <?php echo $admin->couleur;?>">
		<?php echo $admin->getHead();?>
			<div id="menu">
				<ul id="onglets">
				<?php echo $admin->getMenu();?>
				</ul>
				<div id="sousMenu"></div>
				
			</div>
		</div>
		<div id="filariane">Vous êtes ici : <?php echo $admin->getFilAriane();?></div>
		<div id="content">
		<div id="tooltip"></div>
		<?php echo $admin->getContent();?>
		</div>
		<div id="foot" style="background-color: <?php echo $admin->couleur;?>">
		<?php echo $admin->getFoot();?>
		</div>
		
		
		<div id="fondVisioneuse"><div id="cadreVisioneuse"><div id="closer"></div><div id="visioneuse"></div></div></div>
	</body>
</html>
<?php }?>