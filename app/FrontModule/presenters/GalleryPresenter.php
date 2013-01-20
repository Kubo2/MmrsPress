<?php

namespace FrontModule;

/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */
use Nette\Application\UI\Control;
use Nette\Http\User;

class GalleryPresenter extends \BasePresenter {

    public function beforeRender() {
        parent::beforeRender();
        // výpis posledních 4 přidaných fotek z veřejných galerií
        $lastImage = $this->model->getPhotos()->order('id DESC')->limit(4);
        $result = array();
        foreach ($lastImage as $wiew) {
            foreach ($this->model->getGallery()->where('folder', $wiew->folder) as $name) {
                $result[] = array($wiew->folder => array($wiew->photo => $name->name));
            }
        }
        $this->template->wiews_last = $result;

        // výpis veřejných fotogalerií
        $this->template->galerie = $this->model->getGallery()->order('id DESC')->where('public', '1');
        require_once LIBS_DIR . '/galleryFunction.php'; // funkce pro náhodný obrázek (libs)

        $panel = $this->model->getSettings()->select("*")->where('select', 'photoPanel')->fetch();
        if (empty($panel)) {
            $this->template->photoPanel = 0;
        } else {
            $this->template->photoPanel = $panel->public;
        }
    }

}