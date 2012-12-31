<?php

namespace FrontModule;

/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */
class RubrikyPresenter extends \BasePresenter {

    protected function beforeRender() {
        parent::beforeRender();
        $set_rubriky = $this->getParam('page');
        $set_rubriky = str_replace('_', ' ', $set_rubriky);
        $this->template->rubriky_page = $this->model->getNews()->where(array('section' => $set_rubriky, 'public' => 1));

        $rubriky = $this->model->getSection()->where('section', $set_rubriky)->fetch();
        $this->template->rubriky_nazev = $rubriky->section;

        $this->template->tasks = $this->model->getNews()->where('menu', $set_rubriky);
    }

    public function handleDelete($id) {
        $value = $this->getParam('id');
        if ($this->getUser()->loggedIn) {
            if (isset($value)) {
                $this->model->getNews()->where('id', $id)->delete();
                $this->flashMessage('Zpráva byla smazána.');
                $this->redirect($this->action);
            }
        } else {
            $this->flashMessage('Na tuto operaci nemáte oprávnění.');
            $this->redirect('News:news');
        }
    }

}