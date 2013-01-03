<?php

namespace AdminModule;

/**
 * Description of ContactFormPresenter
 *
 * @author rellik
 */
use Nette\Application\UI\Form;
use Nette\Utils\Paginator;
use Nette\Application\UI\Control;
use Nette\Http\User;

class ContactFormPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    protected function createComponentSetContactForm() {

        $isFormSet = $this->model->getSettings()->where('select', 'contact')->fetch();
        if ($isFormSet == false) {
            $default = '0';
            $email = null;
        } else {
            $default = $isFormSet->public;
            $email = $isFormSet->count;
        }


        $setEmail = $this->model->getUsers();
        foreach ($setEmail as $mail) {
            $emails[$mail->users] = array($mail->id => $mail->email);
        }

        $public = array('1' => 'Ano', '0' => 'Ne');

        $form = new Form();

        $form->addRadioList('public', 'Má být formulář zobrazen?', $public)
                ->setDefaultValue($default)
                ->getSeparatorPrototype()->setName(NULL);


        $form->addSelect('email', 'Příjemce formuláře:', $emails)
                ->setDefaultValue($email);


        $form->addSubmit('create', 'Uložit');

        $form->onSuccess[] = callback($this, 'setContactFormSubmitted');

        return $form;
    }

    public function setContactFormSubmitted(Form $form) {
        if ($this->getUser()->getIdentity()->role == 1) {
            $isExist = $this->model->getSettings()->where('select', 'contact')->count('*');
            if ($isExist == 0) {
                $this->model->getSettings()->insert(array(
                    'select' => 'contact',
                    'count' => intval($form->values->email),
                    'public' => intval($form->values->public)
                ));
                $this->flashMessage('Formulář byl nastaven.');
                $this->redirect('this');
            } else {
                $this->model->getSettings()->where('select', 'contact')->update(array(
                    'count' => intval($form->values->email),
                    'public' => intval($form->values->public)
                ));
                $this->flashMessage('Formulář byl nastaven.');
                $this->redirect('this');
            }
        } else {
            $this->flashMessage('Na toto nastavení musíte mít práva hlavního administrátora.');
                $this->redirect('this');
        }
    }

}