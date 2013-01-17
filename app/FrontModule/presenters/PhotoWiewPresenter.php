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

class PhotoWiewPresenter extends \BasePresenter {

    public function beforeRender() {
        parent::beforeRender();
        $this->template->gallery = $this->model->getGallery()->order('id ASC')->where('name', $this->getParam('gallery'));
        $this->template->galleryName = $this->model->getGallery()->where('name', $this->getParam('gallery'));
        $folder = $this->template->gallery = $this->model->getGallery()->where('name', $this->getParam('gallery'))->fetch();

        $this->template->photos = $this->model->getPhotos()->order('id DESC')->where("folder", $folder->folder);
    }
}