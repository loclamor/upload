<?php

session_cache_limiter('public');
require_once 'conf/init.php';



header('Content-type:image/jpg');


//getPhoto.php?uniqidPhoto=$2
if (isset($_GET['uniqidPhoto']) and !empty($_GET['uniqidPhoto'])) {
    $uniqidPhoto = $_GET['uniqidPhoto'];
    $photo = Gestionnaire::getGestionnaire('photo')->getOneOf(array('uniqid' => $uniqidPhoto));
    if ($photo instanceof Bdmap_Photo) {
        //TODO : actions sur la privacit�, � voir si utile

        $photo->setSeen( $photo->getSeen() + 1 );
        $photo->enregistrer( array('seen'));

        header("Content-Disposition: inline; filename=\"{$photo->getLegende()}\";");

        if (isset($_GET['minType']) and isset($_GET['minSize']) and !empty($_GET['minType']) and !empty($_GET['minSize'])) {
            readfile($photo->getMinUrl($_GET['minType'], $_GET['minSize']));
        } else {
            readfile($photo->getUrl());
        }
    } else {
        //photo d'ereur

        header("Content-Disposition: inline; filename=\"Photo indisponible\";");
        readfile("img/appareil-photo.jpg");
    }
} else {
    //photo d'ereur

    header("Content-Disposition: inline; filename=\"Photo indisponible\";");
    readfile("img/appareil-photo.jpg");
}