<?php

namespace FrontModule;

/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */
use Nette\Application\UI\Form,
    Nette\Security as NS;

class SignPresenter extends \BasePresenter {

    protected function createComponentSignInForm() {
        $form = new Form;
        $form->addText('users', 'Username:')
                ->setRequired('Zadejte jméno.');

        $form->addPassword('password', 'Password:')
                ->setRequired('Zadejte heslo.');

        $form->addCheckbox('remember', 'Pamatovat na tomto počítači');

        $form->addSubmit('send', 'Přihlásit');

        $form->onSuccess[] = callback($this, 'signInFormSubmitted');
        return $form;
    }

    public function signInFormSubmitted($form) {
        $active = $this->model->getUsers()->where('users', $form->values->users)->fetch();
        $isActive = $active->active;

        try {
           // if ($isActive == 1) {
                $values = $form->getValues();
                if ($values->remember) {
                    $this->getUser()->setExpiration(10800, FALSE);
                } else {
                    $this->getUser()->setExpiration('+ 20 minutes', TRUE);
                }
                $this->getUser()->login($values->users, $values->password);
                $this->flashMessage('Přihlášení proběhlo úspěšně.');
                $this->redirect('News:news');
           /* } else {
                $this->flashMessage('Přihlášení odmítnuto. Pro přihlášení musíte aktivovat svůj 
                účet pomocí zprávky, která vám byla odeslána na email uvedený při registraci.');
                $this->redirect('News:news');
            }*/
        } catch (NS\AuthenticationException $e) {
            $form->addError($e->getMessage());
        }
    }

    public function actionOut() {
        $this->getUser()->logout(TRUE);
        $this->flashMessage('Byli jse odhlášeni.');
        $this->redirect('News:news');
    }

}
