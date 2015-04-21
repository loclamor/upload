<?php
require_once 'conf/init.php';

$site = new Site_Upload();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title><?php echo $site->getTitle();?></title>
		<link type="image/x-icon" href="img/upload-ico-32x32.png" rel="shortcut icon"/>

		<link rel="stylesheet" media="screen" type="text/css" title="style" href="css/bootstrap.min.css" />
		<link rel="stylesheet" media="screen" type="text/css" title="style" href="style/supplement.css" />
		<link media="all" type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" rel="stylesheet">
		<link media="all" type="text/css" href="css/fileuploader.css" rel="stylesheet">
		
		 
		<script src="js/jquery-1.7.1.min.js" type="text/javascript" language="javascript" ></script>
		
		<script src="js/jquery-ui-1.8.21.custom.min.js" type="text/javascript" language="javascript" ></script>
		<script src="js/functions.js" type="text/javascript" language="javascript" ></script>
		<script src="js/bootstrap.min.js" type="text/javascript" language="javascript" ></script>
		<script src="js/fileuploader.js" type="text/javascript" language="javascript" ></script>
                
		<?php if(APPLICATION_ENV == 'prod') {?>
		<script type="text/javascript">
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-30105797-1']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>
		<?php }?>
		
	</head>
	<body>
		<div id="head" class="row-fluid" >
			<?php echo $site->getHead();?>
		</div>
		<nav class="navbar">
			<div class="navbar-inner">
				<div class="container" id="menu">
					<?php echo $site->getMenu();?>
				</div>
			</div>
		</nav>
		<div class="row-fluid filariane">
			<div id="filariane" class="span12">Vous &ecirc;tes ici : <?php echo $site->getFilAriane();?></div>
		</div>
		<div id="content" class="row-fluid">
			<div class="span12 content-container">
				<div class="row-fluid">
					<div id="tooltip"></div>
					<?php echo $site->getContent();?>
				</div>
			</div>
		</div>
		<div id="foot" class="row-fluid" >
			<div class="span12 footer"><?php echo $site->getFoot();?></div>
		</div>
		<div id="notification-box"></div>
                
                <script type="text/javascript" language="javascript">
                    if( screen.width < 800 ) {
                        $("body").addClass("mobile");
                    }
                </script>
	</body>
</html>
<?php 
require_once 'conf/fini.php';?>