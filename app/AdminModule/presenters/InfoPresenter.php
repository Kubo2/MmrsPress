<?php

namespace AdminModule;

/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */
class InfoPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
        if ($this->user_role != 1){
            $this->redirect('AddNews:default');
        }
    }

    public function beforeRender() {
        parent::beforeRender();
        /*         * **** Výpis informací o webu  **** */
        $this->template->info_user = $this->getUser()->roles;
        // News - počet aktualit
        $this->template->tasks_aktuality = $this->model->getNews()->where("section", 'news')->count("*");
        // články - počet článků
        $pocet_clanky = $this->model->getNews()->where('section <> ?', 'news')->count("*");
        $this->template->tasks_clanky = $pocet_clanky;
        $this->template->publicPage = $this->model->getNews()->where(array('section <> ?' => 'news', 'public' => 0))->count("*");
        // datum - datum poslední News/článku
        $this->template->datum = $this->model->getNews()->order('id DESC')->limit(1);
        $this->template->tasks_date = $pocet_clanky;
        // Kniha návštěv - počet příspěvků
        $this->template->tasks_kniha = $this->model->getBook()->count("*");

        // Fotogalerie
        $this->template->tasks_gallery = $this->model->getGallery()->count("*");

        $this->template->set_gallery = $this->model->getGallery()->where('public', 0)->count('*');

        // Počet fotek
        $this->template->tasks_foto = $this->model->getPhotos()->count("*");
        // nastavení aktualit
        foreach ($this->model->getSettings()->where('select', 'news') as $num) {
            $pocet = $num->count;
            $set_aktual = $num->public;
        }
        $this->template->sectionCount = $this->model->getSection()->count('*');
    }

}