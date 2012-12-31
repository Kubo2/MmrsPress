<?php
namespace FrontModule;
/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */

class PagePresenter extends \BasePresenter {

    public function beforeRender() {
        parent::beforeRender();
        $wiews = $this->getParam('wiew');
        $this->template->wiewMenu = $this->model->getNews()->where("id", $wiews);
    }

    public function handleDelete($id) {
        $value = $this->getParam('id');
        if (isset($value)) {
            if ($this->getUser()->loggedIn) {
                $this->model->getNews()->where('id', $id)->delete();
                $this->flashMessage('Stránka byla smazána.');
                $this->redirect(':Admin:Info:');
            } else {
                $this->flashMessage('Na tuto operaci nemáte oprávnění.');
            }
        }
    }

}