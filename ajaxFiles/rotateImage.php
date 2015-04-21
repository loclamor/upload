<?php

//TODO secure this

$return = array( 'state' => 'error' );

if( isset($_GET['rotate']) && !empty($_GET['rotate']) 
    && isset($_GET['imageId']) && !empty($_GET['imageId'])
        ) {
    $photo = Gestionnaire::getGestionnaire('photo')->getOne( $_GET['imageId'] );
    if( $photo instanceof Bdmap_Photo ){
        $urlImage = $photo->getUrl();
        $newUrl = $urlImage;
//        $path_parts = pathinfo( $urlImage );
//        $newUrl = $path_parts['dirname'].'/'.$path_parts['filename'].'.r.'.$path_parts['extension'];
        
        $newUrl = rotateImage( $urlImage, $_GET['rotate'], $newUrl );
        
        if( $newUrl !== false ) {
            $photo->setUrl( $newUrl );
            $photo->enregistrer();
            
            //before returning, delete all min files to force refresh
            $path_parts = pathinfo( $urlImage );
            $mask = $path_parts['dirname'].'/'.$path_parts['filename'].'.min*';
            //$mask = "*.jpg";
            array_map( "unlink", glob( $mask ) );
            
            $return['state'] = 'success';
            $return['newUrl'] = $newUrl;
            $return['newMinUrl'] =  $photo->getMinUrl('H', 179);
            $return['newMinPrivateUrl'] =  $photo->getMinPrivateUrl('H', 179);
        }
        else {
            
        }
        
    }
    else {
        
    }
}

echo json_encode( $return );