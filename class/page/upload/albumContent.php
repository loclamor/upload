<?php

class Page_Upload_AlbumContent extends Page {

    public function controller($albumId) {
        $album = Gestionnaire::getGestionnaire('album')->getOne($albumId);
        if ($album instanceof Bdmap_Album) {
            $this->nom = $album->getNom();

            $this->uniqidAlbum = $album->getUniqid();
            $this->albumId = $album->getId();
            
            $this->urlFusion = new Url(true);
            $this->urlFusion->addParam('page', 'fusion');
            $this->urlFusion->addParam('noDisplay', 'true'); 

            $this->photos = Gestionnaire::getGestionnaire('photo')->getOf(array('id_album' => $album->getId()));

            $this->others = array();
            $albums = Gestionnaire::getGestionnaire('album')->getOf(array('id_utilisateur' => $_SESSION['upload']['id'], 'is_temp' => 0));
            foreach ($albums as $a) {
                if ($a instanceof Bdmap_Album) {
                    if ($a->getId() != $album->getId())
                        $this->others[] = $a;
                }
            }
        }
        else {
            $this->nom = "album inconnu";
            $this->uniqidAlbum = "0";
            $this->photos = false;
            $this->albumId = "0";
            $this->urlFusion = new Url(true);
        }
        $this->addTitle($this->nom);
    }

}
