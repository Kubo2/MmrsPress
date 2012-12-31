<?php

namespace FrontModule;

/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */
use Nette\Application\UI\Form;
use Nette\Utils\Paginator;
use Nette\Application\UI\Control;
use Nette\Http\User;

class GalleryPresenter extends \BasePresenter {

    public function beforeRender() {
        parent::beforeRender();
        // výpis posledních 4 přidaných fotek z veřejných galerií
        $public = $this->model->getGallery()->order('id ASC')->where('public', '1');
        $wiews_last = array();
        foreach ($public as $wiew) {
            $wiews_last[] = $wiew->folder;
        }
        $this->template->photo_last = $this->model->getPhotos()->where('folder', $wiews_last)->order('id DESC')->limit(4);
        // výpis veřejných fotogalerií
        $this->template->galerie = $this->model->getGallery()
                        ->order('id DESC')->where('public', '1');
        require_once LIBS_DIR . '/galleryFunction.php'; // funkce pro náhodný obrázek (libs)

        $panel = $this->model->getSettings()->select("*")->where('select', 'photoPanel')->fetch();
        if (empty($panel)) {
            $this->template->photoPanel = 0;
        } else {
            $this->template->photoPanel = $panel->public;
        }
    }
}