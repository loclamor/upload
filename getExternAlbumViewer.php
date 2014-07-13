<?php
require_once 'conf/init.php';
$temp = microtime();
$temps = explode(' ', $temp);
$microStart = $temps[1] + $temps[0];

if( !isset( $_GET['uniqidAlbum'] ) || empty( $_GET['uniqidAlbum'] ) ){
    redirect("../404.html");
}
$uniqidAlbum = $_GET['uniqidAlbum'];
$album = Gestionnaire::getGestionnaire('album')->getOneOf( array('uniqid' => $uniqidAlbum) );

if( !$album ) {
    redirect("../404.html");
}

if( ! ($album instanceof Bdmap_Album) ) {
    redirect("../404.html");
}

$photos = Gestionnaire::getGestionnaire('photo')->getOf( array( 'id_album' => $album->getId() ) );

if( !$photos ) {
    redirect("../404.html");
}

$album->setSeen( $album->getSeen() + 1 );
$album->enregistrer( array('seen'));

$albumName = $album->getNom();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Album Viewer : <?php echo $albumName; ?></title>
        <link type="image/x-icon" href="../style/favicon.ico" rel="shortcut icon"/>

        <link rel="stylesheet" media="screen" type="text/css" title="style" href="../css/bootstrap.min.css" />
        <link rel="stylesheet" media="screen" type="text/css" title="style" href="../style/supplement.css" />
        <link media="all" type="text/css" href="../css/smoothness/jquery-ui-1.8.18.custom.css" rel="stylesheet">

        <script src="../js/jquery-1.7.1.min.js" type="text/javascript" language="javascript" ></script>

        <script src="../js/jquery-ui-1.8.21.custom.min.js" type="text/javascript" language="javascript" ></script>
        <script src="../js/functions.js" type="text/javascript" language="javascript" ></script>
        <script src="../js/bootstrap.min.js" type="text/javascript" language="javascript" ></script>

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
        <div class="row-fluid">
            <div id="myCarousel" class="carousel slide">
                <div class="carousel-inner">

                <?php
                foreach ($photos as $p) {
                    if( $p instanceof Bdmap_Photo ) {
                        $privateUrl = "." . $p->getMinPrivateUrl("L", 1000);
                        $captionTitle = $p->getLegende();
                ?>
                    <div class="item">
                        <img src="<?php echo $privateUrl; ?>" alt="">
                        <div class="carousel-caption">
                            <h4><?php echo $captionTitle; ?></h4>
                            &nbsp;Autres tailles : <a href="<?php echo $p->getPrivateUrl(); ?>" >origine</a>
                             - <a href="<?php echo $p->getMinPrivateUrl("L", 1000); ?>" >1000px</a>
                             - <a href="<?php echo $p->getMinPrivateUrl("L", 800); ?>" >800px</a>
                             - <a href="<?php echo $p->getMinPrivateUrl("L", 500); ?>" >500px</a>
                             - <a href="<?php echo $p->getMinPrivateUrl("L", 300); ?>" >300px</a>
                        </div>
                    </div>
                <?php
                    }
                }
                ?>
                </div>
                <a class="left carousel-control" href="#myCarousel" data-slide="prev">&lsaquo;</a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next">&rsaquo;</a>
            </div>
        </div>
        <?php
        $tempFin = microtime();
        $tempsFin = explode(' ', $tempFin);
        $microEnd = $tempsFin[1] + $tempsFin[0];
        $microTime = round(($microEnd - $microStart),3)
        ?>
        <div class="row-fluid" id="foot">
            <div class="span12 footer">
                <div id="root_foot">
                    <span id="foot_version">MondoPhoto Upload Service v0.1</span>
                    <span class="separator"> - </span>
                    <span id="foot_copyright">&copy; 2012 loclamor</span>
                    <span class="separator"> - </span>
                    <span id="foot_time">page g&eacute;n&eacute;r&eacute;e en <?php echo $microTime; ?> secondes</span>
                </div>
            </div>
        </div>
    <script>
        $("#myCarousel").carousel();
    </script>

    </body>
</html>