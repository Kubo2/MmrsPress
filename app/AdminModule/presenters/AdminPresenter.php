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

class AdminPresenter extends \BasePresenter {

    protected function createComponentNewPassForm() {
        $form = new Form;
        
        $form->addText('email', 'E-mail:')
                ->addCondition($form::FILLED)
                ->addRule($form::EMAIL, 'Nesprávně uvedený email!');
        
        $form->addText('web', 'Web:')
                ->setEmptyValue('http://')
                ->addCondition($form::FILLED)
                ->addRule($form::URL, 'Nesprávně uvedená adresa webu!');
                
        $form->addPassword('password', 'Nové heslo:')
                ->setRequired('Zvolte si heslo')
                ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky', 4);

        $form->addPassword('passwordVerify', 'Heslo pro kontrolu:')
                ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu')
                ->addRule(Form::EQUAL, 'Hesla se neshodují', $form['password']);
        
        $form->addHidden('id')
                ->setDefaultValue($this->getParam('id'));

        $form->addSubmit('send', 'Nastavit');

        $form->onSuccess[] = callback($this, 'newPassFormSubmitted');
        
        return $form;
    }

    public function newPassFormSubmitted($form) {
        $this->model->getUsers()->where('users', 'Admin')->update(array(
            'password' => md5($form->values->password),
            'email' => $form->values->email,
            'web' => $form->values->web
        ));
        $this->flashMessage('Heslo bylo změněno.');
        $this->redirect(':Front:News:news');
    }

}