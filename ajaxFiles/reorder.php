<?php

if( isset( $_GET['idPhotoReorder'] ) and !empty( $_GET['idPhotoReorder'] ) 
        and isset( $_GET['photoIndex'] ) and ( !empty( $_GET['photoIndex'] ) || $_GET['photoIndex'] === 0 || $_GET['photoIndex'] === '0' ) ) {
    
    $photo = Gestionnaire::getGestionnaire('photo')->getOne( $_GET['idPhotoReorder'] );
    
    if( $photo instanceof Bdmap_Photo ) {
        
        $photosAlbum = Gestionnaire::getGestionnaire('photo')->getOf(array('id_album' => $photo->getIdAlbum()), 'ordering, id');
        
        $newIndex = $_GET['photoIndex'];
        $oldIndex = $photo->getOrdering();
        
        foreach ($photosAlbum as $p) {
            if( $p instanceof Bdmap_Photo ) {
                if( $p->id != $photo->id ) {
                    if( $newIndex < $oldIndex ) {
                        if( $p->getOrdering() >= $newIndex && $p->getOrdering() < $oldIndex ) {
                            $p->setOrdering ($p->getOrdering() + 1);
                            $p->enregistrer( array('ordering') );
                        }
                    }
                    //if equals do nothing :)
                    if( $newIndex > $oldIndex ) {
                        if( $p->getOrdering() > $oldIndex && $p->getOrdering() < $newIndex ) {
                            $p->setOrdering ($p->getOrdering() - 1);
                            $p->enregistrer( array('ordering') );
                        }
                    }
                }
            }
        }
        
        $photo->setOrdering( $newIndex );
        if( $newIndex > $oldIndex ) {
            $photo->setOrdering($photo->getOrdering() - 1);
        }
        $photo->enregistrer( array('ordering') );
    }
    else {
        echo '{error: "Not a Photo"}';
    }
}
else {
    echo '{error: "an field is empty"}';
}