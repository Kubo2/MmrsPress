<?php

namespace FrontModule;

/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */
class NewsPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        // zjištění nastavení superadmina
        foreach ($this->model->getUsers()->where('users', 'Admin') as $pass) {
            $isPass = $pass->password;
        }
        // pokud superadmin není nastaven přesměruje se na nastavení   
        if (empty($isPass)) {
            $this->redirect('Install:default');
        }
    }

    public function beforeRender() {
        parent::beforeRender();

        // přesměrování pokud neexituje žádná aktualita
        $aktuality_set = $this->model->getSettings()->where('select', 'news')->count();
        if ($aktuality_set > 0) {
            $news_set = $this->model->getSettings()->where('select', 'news')->fetch();
            $count = $news_set->count;
            $set_aktual = $news_set->public;
        } else {
            $count = 4;
            $set_aktual = 0;
        }
        $date = Date("Y-m-d");
        // přesměrování pokud není žádný článek
        $pages_count = $this->model->getNews()->where(array('section <> ?' => 'news', 'public' => 1))->count('*');

        // přesměrování pokud enní fotogalerie
        $gallery_public = $this->model->getGallery()->where('public', 1)->count('*');

        // přesměrování pokud není kniha návštěv
        $book_public = $this->model->getSettings()->where(array('select' => 'book', 'public' => 1))->count("*");

        if ($this->model->getNews()->where(array('section' => 'news', 'public' => '1', 'publicDate <= ?' => $date))->count('*') == 0 OR $set_aktual == 0) {
            if ($pages_count == 0) {
                if ($gallery_public == 0) {
                    if ($book_public == 0) {
                        $this->redirect('EmptyPage:default');
                    } else {
                        $this->redirect('Book:default');
                    }
                } else {
                    $this->redirect('Gallery:default');
                }
            } else {
                $page_id = $this->model->getNews()->where(array('section <> ?' => 'news', 'public' => 1))->fetch();
                $idPage = $page_id->id;
                $this->redirect('Page:default', array('wiew' => $idPage));
            }
        }

        if (empty($count)) {
            $count = 1;
        }

        $vp = new \VisualPaginator($this, 'vp');
        $paginator = $vp->getPaginator();
        $paginator->itemsPerPage = $count;
        $items = $this->model->getNews()->where(array('section' => $this->action, 'public' => 1, 'publicDate <= ?' => $date));
        $paginator->itemCount = count($items);

        $this->template->tasks = $this->model->getNews()
                ->order('id DESC')->where(array('section' => $this->action, 'public' => 1, 'publicDate <= ?' => $date))
                ->limit($paginator->itemsPerPage, $paginator->offset);
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