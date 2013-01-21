<?php

namespace AdminModule;

/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 * @extension Pavel Straka
 * 
 */
use Nette\Application\UI\Form;
use Nette\Utils\Paginator;
use Nette\Application\UI\Control;
use Nette\Http\User;

class RssPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    protected function createComponentSetRssForm() {

        //stav rss
        $isRss = $this->model->getSetRss()->count('*');
        if ($isRss > 0) {
            $rss = $this->model->getSetRss()->select("*")->fetch();

            $news = ($rss->news == 1) ? 1 : 0;
            $pages = ($rss->pages == 1) ? 1 : 0;
            $book = ($rss->book == 1) ? 1 : 0;
        } else {
            $news = 0;
            $pages = 0;
            $book = 0;
        }


        $form = new Form();

        $form->addCheckbox('news', 'Rss aktualit')
                ->setDefaultValue($news);
        $form->addCheckbox('pages', 'Rss stránek')
                ->setDefaultValue($pages);
        $form->addCheckbox('book', 'Rss knihy návštěv')
                ->setDefaultValue($book);

        $form->addSubmit('create', 'Nastavit');

        $form->onSuccess[] = callback($this, 'setRssFormSubmitted');

        return $form;
    }

    public function setRssFormSubmitted(Form $form) {
        if ($this->user_role == 3) {
            $this->flashMessage('Tato  operace není v demo módu dostupná.');
            $this->redirect('this');
        } else {
            $isRss = $this->model->getSetRss()->count('*');
            if ($isRss == 0) {
                $this->model->getSetRss()->insert(array(
                    'news' => $form->values->news,
                    'pages' => $form->values->pages,
                    'book' => $form->values->book
                ));
                $this->flashMessage('Odběr Rss byl nastaven.');
                $this->redirect('this');
            } else {
                $this->model->getSetRss()->update(array(
                    'news' => $form->values->news,
                    'pages' => $form->values->pages,
                    'book' => $form->values->book
                ));
                $this->flashMessage('Odběr Rss byl nastaven.');
                $this->redirect('this');
            }
        }
    }

}
