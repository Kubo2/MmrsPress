<?php

namespace AdminModule;

/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */
class PagesPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    public function beforeRender() {
        parent::beforeRender();
        $num = array(0 => 'page');
        foreach ($this->model->getSection() as $sections) {
            $num[] .= $sections->section;
        }
        $vp = new \VisualPaginator($this, 'vp');
        $paginator = $vp->getPaginator();
        $paginator->itemsPerPage = 10;
        $items = $this->model->getNews()->where('section <> ?', 'news');
        $paginator->itemCount = count($items);
        $this->template->pages = $this->model->getNews()
                ->where('section <> ?', 'news')->order('date DESC')
                ->limit($paginator->itemsPerPage, $paginator->offset);
    }

    public function handleDelete($id) {
        $value = $this->getParam('id');
        if (isset($value)) {
            if ($this->getUser()->loggedIn) {
                $this->model->getNews()->where('id', $id)->delete();
                $this->flashMessage('Zpráva byla smazána.');
                $this->redirect($this->action);
            } else {
                $this->flashMessage('Na tuto operaci nemáte oprávnění.');
            }
        }
    }

    public function handlePublic($id) {
        $value = $this->getParam('id');
        if (isset($value)) {
            if ($this->getUser()->loggedIn) {
                if ($this->user_role == 3) {
                    $this->flashMessage('Tato  operace není v demo módu dostupná.');
                    $this->redirect('this');
                } else {
                    $this->model->getNews()->where('id', $id)->update(array(
                        'public' => 1,
                        'date' => new \DateTime()
                    ));
                    $this->flashMessage('Stránka byla zveřejněna.');
                    $this->redirect($this->action);
                }
            } else {
                $this->flashMessage('Na tuto operaci nemáte oprávnění.');
            }
        }
    }

    public function handlehidden($id) {
        $value = $this->getParam('id');
        if (isset($value)) {
            if ($this->getUser()->loggedIn) {
                $this->model->getNews()->where('id', $id)->update(array(
                    'public' => 0
                ));
                $this->flashMessage('Stránka byla nasatvena jako skrytá.');
                $this->redirect($this->action);
            } else {
                $this->flashMessage('Na tuto operaci nemáte oprávnění.');
            }
        }
    }

}