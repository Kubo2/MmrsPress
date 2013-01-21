<?php

namespace AdminModule;

/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */
class SetCounterPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

// nastavení počitadla jako veřejné
    public function handleVerejna($id) {
        if ($this->user_role == 3) {
            $this->flashMessage('Tato  operace není v demo módu dostupná.');
            $this->redirect('this');
        } else {
            if (isset($id)) {
                $this->model->getSettings()->where('id', $id)->update(array(
                    'public' => '1'
                ));
                $this->flashMessage('Počitadlo návštěv bylo nastaveno jako veřejné.');
                $this->redirect('this');
            } else {
                $this->model->getSettings()->insert(array(
                    'select' => 'counter',
                    'public' => '1'
                ));
                $this->flashMessage('Počitadlo návštěv bylo nastaveno jako veřejné.');
                $this->redirect('this');
            }
        }
    }

// nastavení počitadla jako skryté
    public function handleSkryta($id) {
        if ($this->user_role == 3) {
            $this->flashMessage('Tato  operace není v demo módu dostupná.');
            $this->redirect('this');
        } else {
            if (isset($id)) {
                $this->model->getSettings()->where('id', $id)->update(array(
                    'public' => '0'
                ));
                $this->flashMessage('Počitadlo návštěv bylo nastaveno jako skryté.');
                $this->redirect('this');
            } else {
                $this->model->getSettings()->insert(array(
                    'select' => 'counter',
                    'public' => '0'
                ));
                $this->flashMessage('Počitadlo návštěv bylo nastaveno jako skryté.');
                $this->redirect('this');
            }
        }
    }

// vymazání počitadla
    public function handleCounter() {
        if ($this->getUser()->loggedIn) {
            if ($this->user_role == 3) {
                $this->flashMessage('Tato  operace není v demo módu dostupná.');
                $this->redirect('this');
            } else {
                $this->model->getCounter()->delete();
                $this->model->getCounter_all()->delete();
                $this->flashMessage('Počitadlo návštěv bylo vymazáno.');
                $this->redirect('this');
            }
        }
    }

}