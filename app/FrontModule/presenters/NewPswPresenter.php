<?php

namespace FrontModule;

/**
 * Description of NewPswPresenter
 *
 * @author rellik
 */
use Nette\Application\UI\Form;
use Nette\Utils\Paginator;
use Nette\Application\UI\Control;
use Nette\Http\User;

class NewPswPresenter extends \BasePresenter {

    // new password
    protected function createComponentNewPswForm() {

        $form = new Form;
        $form->addGroup('Nové heslo');

        $form->addPassword('passwordOld', 'Současné heslo:')
                ->setRequired('Zadejte současné heslo')
                ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky', 4);

        $form->addPassword('password', 'Nové heslo:')
                ->setRequired('Zvolte si heslo')
                ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky', 4);

        $form->addPassword('passwordVerify', 'Heslo pro kontrolu:')
                ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu')
                ->addRule(Form::EQUAL, 'Hesla se neshodují', $form['password']);

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = callback($this, 'newPswFormSubmitted');

        return $form;
    }

    public function newPswFormSubmitted($form) {
        if (md5($form->values->passwordOld) === $this->getUser()->getIdentity()->password) {
            $this->model->getUsers()->where('users', $this->getUser()->getIdentity()->users)->update(array(
                'password' => md5($form->values->password)
            ));
            $this->flashMessage('Heslo bylo změněno.');
            $this->redirect('MyInfo:');
        } else {
            $this->flashMessage('Původní heslo se neshoduje!.');
        }
    }

}