<?php
namespace AdminModule;
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

class PhotosPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    public function beforeRender() {
        parent::beforeRender();
        $this->template->galerie = $this->model->getGallery()
                        ->where('folder', $this->getParam('folder'))->limit(1);

        $this->template->fotky = $this->model->getphotos()
                        ->order('id ASC')->where('folder', $this->getParam('folder'));
    }

    /*     * ********** Funkce na mazání fotek *********** */

    public function handleDeleteFoto($foto) {
        $slozka = $this->getParam('folder');
        $foto = $this->getParam('photo');
        if (file_exists(WWW_DIR . '/gallery/' . $slozka . '/' . $foto)) {
            unlink(WWW_DIR . '/gallery/' . $slozka . '/' . $foto);
        }
        if (file_exists(WWW_DIR . '/gallery/' . $slozka . '/nahledy/' . $foto)) {
            unlink(WWW_DIR . '/gallery/' . $slozka . '/nahledy/' . $foto);
        }
        $this->model->getPhotos()->where(array('photo' => $foto, 'folder' => $slozka))->delete();
        $this->flashMessage('Fotografie byla smazána.');
        $this->redirect('this', array('folder' => $slozka));
    }

}