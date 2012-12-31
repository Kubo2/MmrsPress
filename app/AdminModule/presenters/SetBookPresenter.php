<?php
namespace AdminModule;
/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */
class SetBookPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

// nastavení knihy jako veřejné
    public function handleVerejna($id) {
        if (isset($id)) {
            $this->model->getSettings()->where('id', $id)->update(array(
                'public' => '1'
            ));
            $this->flashMessage('Kniha návštěv byla nastavena jako veřejná.');
            $this->redirect('this');
        } else {
            $this->model->getSettings()->insert(array(
                'select' => 'book',
                'public' => '1'
            ));
            $this->flashMessage('Kniha návštěv byla nastavena jako veřejná.');
            $this->redirect('this');
        }
    }

// nastavení knihy jako skryté
    public function handleSkryta($id) {
        if (isset($id)) {
            $this->model->getSettings()->where('id', $id)->update(array(
                'public' => '0'
            ));
            $this->flashMessage('Kniha návštěv byla nastavena jako skrytá.');
            $this->redirect('this');
        } else {
            $this->model->getSettings()->insert(array(
                'select' => 'book',
                'public' => '0'
            ));
            $this->flashMessage('Kniha návštěv byla nastavena jako skrytá.');
            $this->redirect('this');
        }
    }

}