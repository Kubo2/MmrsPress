<?php
namespace AdminModule;
/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */
use Nette\Application\UI\Form;
use Nette\Http\User;

class NewPassPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    protected function createComponentNewPassForm() {
        $form = new Form;

        $form->addPassword('password', 'Nové heslo:')
                ->setRequired('Zvolte si heslo')
                ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky', 4);

        $form->addPassword('passwordVerify', 'Heslo pro kontrolu:')
                ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu')
                ->addRule(Form::EQUAL, 'Hesla se neshodují', $form['password']);
        $form->addHidden('id')
                ->setDefaultValue($this->getParam('id'));

        $form->addSubmit('send', 'Změnit');

        $form->onSuccess[] = callback($this, 'newPassFormSubmitted');
        return $form;
    }

    public function newPassFormSubmitted($form) {
        if ($this->user_role == 1) {
            $this->model->getUsers()->where('id', $form->values->id)->update(array(
                'password' => md5($form->values->password)
            ));
            $this->flashMessage('Heslo bylo změněno.');
            $this->redirect('Registration:');
        } else {
            $this->flashMessage('Pro tuto operaci musíte mít práva hlavního administrátora.');
            $this->redirect('Registration:');
        }
    }

}