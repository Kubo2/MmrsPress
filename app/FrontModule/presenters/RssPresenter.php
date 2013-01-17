<?php
namespace FrontModule;
/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */

class RssPresenter extends \BasePresenter {

    protected function beforeRender() {
        parent::beforeRender();
        $this->template->kniha_rss = $this->model->getBook();
        $this->template->page_rss = $this->model->getNews()->where('section <> ?', 'news');
    }

}
