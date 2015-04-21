<?php

class Page_Upload_DoMerge extends Page {

    public function controller($mixed) {
        $sourceId = $mixed['source'];
        $destId = $mixed['dest'];

        $urlAction = new Url(true);
        $urlAction->addParam('page', 'album');
        $urlAction->addParam('idAlbum', $destId);

        $log = new Logger('./logs');

        $albumSource = Gestionnaire::getGestionnaire('Album')->getOne($sourceId);
        $albumDest = Gestionnaire::getGestionnaire('Album')->getOne($destId);

        if ($albumSource && $albumDest) {

            //get each photo of sourceId and set their album's Id to destId
            $photosSource = Gestionnaire::getGestionnaire('Photo')->getOf(array('id_album' => $albumSource->getId()));
            foreach ($photosSource as $photo) {
                if( $photo instanceof Bdmap_Photo ) {
                    $photo->setIdAlbum( $albumDest->getId() );
                    $photo->enregistrer();
                }
            }
            //finaly delete the old source album
            $albumSource->supprimer();

            $log->log('success', 'success_merge', 'Success du merge de ' . $sourceId . " dans " .$destId, Logger::GRAN_MONTH);
            $urlAction->addParam('notify', 'Albums fusionnes.');
        } else {
            unset($_SESSION['upload']['isConnect']);
            unset($_SESSION['upload']['id']);
            $log->log('erreurs', 'erreurs_merge', 'Echec du merge de ' . $sourceId . ' vers ' . $destId, Logger::GRAN_MONTH);
            $urlAction->addParam('idAlbum', $sourceId);
            $urlAction->addParam('notify', 'Echec de la fusion.');
        }




        redirect($urlAction->getUrl());
        echo 'Vous avez �t� connect�.<br/><a href="' . $urlAction->getUrl() . '">retour accueil</a>';
    }

}
