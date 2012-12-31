<?php

namespace FrontModule;

/**
 * Description of MyInfoPresenter
 *
 * @author rellik
 */
class MyInfoPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    public function beforeRender() {
        parent::beforeRender();
        $info = $this->model->getNews();
        $this->template->newsCount = $this->model->getNews()->where(array('section' => 'news', 'autor' => $this->getUser()->getIdentity()->users))->count('*');
        $this->template->pageCount = $this->model->getNews()->where(array('section <> ?' => 'news', 'autor' => $this->getUser()->getIdentity()->users))->count('*');
        $this->template->bookCount = $this->model->getBook()->where('name', $this->getUser()->getIdentity()->users)->count('*');
        $this->template->galleryCount = $this->model->getGallery()->where('autor', $this->getUser()->getIdentity()->users)->count('*');
    }

}