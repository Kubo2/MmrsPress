<?php

namespace AdminModule;

/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */
use Nette\Application\UI\Form;
use Nette\Utils\Paginator;
use Nette\Application\UI\Control;
use Nette\Http\User;

class SetNewsPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    protected function createComponentSetNewsForm() {
        $pocet = $this->model->getSettings()->where('select', 'news')->fetch();
        for ($i = 1; $i <= 10; $i++) {
            $num[$i] = $i;
        }
        $form = new Form();
        if (!empty($pocet->count)) {
            $form->addSelect('pocet', 'Počet aktualit na stránku', $num)
                    ->setDefaultValue($pocet->count);
        } else {
            $form->addSelect('pocet', 'Počet aktualit na stránku', $num);
        }

        $form->addSubmit('create', 'Uložit');

        $form->onSuccess[] = callback($this, 'setNewsFormSubmitted');

        return $form;
    }

    public function setNewsFormSubmitted(Form $form) {
        if ($this->user_role == 3) {
            $this->flashMessage('Tato  operace není v demo módu dostupná.');
            $this->redirect('this');
        } else {
            $isExist = $this->model->getSettings()->where('select', 'news')->count('*');
            if ($isExist == 0) {
                $this->model->getSettings()->insert(array(
                    'select' => 'news',
                    'count' => intval($form->values->pocet)
                ));
                $this->flashMessage('Počet aktualit byl nastaven.');
                $this->redirect('this');
            } else {
                $this->model->getSettings()->where('select', 'news')->update(array(
                    'count' => intval($form->values->pocet)
                ));
                $this->flashMessage('Počet aktualit byl nastaven.');
                $this->redirect('this');
            }
        }
    }

    // nastavení knihy jako veřejné
    public function handlePublic($id) {
        if ($this->user_role == 3) {
            $this->flashMessage('Tato  operace není v demo módu dostupná.');
            $this->redirect('this');
        } else {
            if (isset($id)) {
                $this->model->getSettings()->where('id', $id)->update(array(
                    'public' => '1'
                ));
                $this->flashMessage('Aktuality byly nastaveny jako veřejné.');
                $this->redirect('this');
            } else {
                $this->model->getSettings()->insert(array(
                    'select' => 'news',
                    'public' => '1'
                ));
                $this->flashMessage('Aktuality byly nastaveny jako veřejné.');
                $this->redirect('this');
            }
        }
    }

// nastavení knihy jako skryté
    public function handleHidden($id) {
        if ($this->user_role == 3) {
            $this->flashMessage('Tato  operace není v demo módu dostupná.');
            $this->redirect('this');
        } else {
            if (isset($id)) {
                $this->model->getSettings()->where('id', $id)->update(array(
                    'public' => '0'
                ));
                $this->flashMessage('Aktuality byly nastaveny jako skryté.');
                $this->redirect('this');
            }
        }
    }

}