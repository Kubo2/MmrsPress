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

class RegistrationPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    public function beforeRender() {
        parent::beforeRender();
        $this->template->userWiews = $this->model->getUsers()->order('id DESC');
    }

    protected function createComponentRegistrationForm() {
        $setRole = array(
            '1' => 'Kompletní',
            '2' => 'Omezené',
            '3' => 'Demo'
        );
        $form = new Form();
        $form->addGroup('Nový uživatel');

        $form->addText('users', 'Jméno:')
                ->addRule(Form::FILLED, 'Zadejte prosím své jméno');

        $form->addText('email', 'E-mail:')
                ->addRule($form::FILLED, 'Email je nutné vyplnit')
                ->addRule($form::EMAIL, 'Nesprávně uvedený email!');

        $form->addText('web', 'Web:')
                ->setEmptyValue('http://')
                ->addCondition($form::FILLED)
                ->addRule($form::URL, 'Nesprávně uvedená adresa webu!');

        $form->addSelect('role', 'Oprávnění:', $setRole);

        $form->addPassword('password', 'Heslo:')
                ->setRequired('Zvolte si heslo')
                ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň %d znaky', 4);

        $form->addPassword('passwordVerify', 'Heslo pro kontrolu:')
                ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu')
                ->addRule(Form::EQUAL, 'Hesla se neshodují', $form['password']);


        $form->addSubmit('create', 'Vytvořit');

        $form->onSuccess[] = callback($this, 'registrationFormSubmitted');

        return $form;
    }

    public function handleDelete($id) {
        $value = $this->getParam('id');
        if (isset($value)) {
            if ($this->user_role == 1) {
                $this->model->getUsers()->where('id', $id)->delete();
                $this->flashMessage('Uživatel byl smazán.');
                $this->redirect('this');
            } else {
                $this->flashMessage('Pro tuto operaci musíte mít práva hlavního administrátora.');
                $this->redirect('this');
            }
        }
    }

    public function registrationFormSubmitted(Form $form) {
        $isExist = $this->model->getUsers()->where('users', $form->values->users)->count('*');
        if ($isExist == 0) {
            if ($this->user_role == 1) {
                $this->model->getUsers()->insert(array(
                    'users' => $form->values->users,
                    'email' => $form->values->email,
                    'web' => $form->values->web,
                    'role' => $form->values->role,
                    'password' => md5($form->values->password),
                    'active' => 1
                ));
                $this->flashMessage('Registrace proběhla úspěšně.');
                $this->redirect('default');
            } else {
                $this->flashMessage('Pro tuto operaci musíte mít práva hlavního administrátora.');
                $this->redirect('this');
            }
        } else {
            $this->flashMessage('Uživatel již existuje.');
            $this->redirect('this');
        }
    }

    // nastavení knihy jako veřejné
    public function handleVerejna($id) {
        if ($this->user_role == 1) {
            if (isset($id)) {
                $this->model->getSettings()->where('id', $id)->update(array(
                    'public' => '1'
                ));
                $this->flashMessage('Registrace uživatelů byla povolena.');
                $this->redirect('this');
            } else {
                $this->model->getSettings()->insert(array(
                    'select' => 'user',
                    'public' => '1'
                ));
                $this->flashMessage('Registrace uživatelů byla povolena.');
                $this->redirect('this');
            }
        } else {
            $this->flashMessage('Pro tuto operaci musíte mít práva hlavního administrátora.');
            $this->redirect('this');
        }
    }

// nastavení knihy jako skryté
    public function handleSkryta($id) {
        if ($this->user_role == 1) {
            if (isset($id)) {
                $this->model->getSettings()->where('id', $id)->update(array(
                    'public' => '0'
                ));
                $this->flashMessage('Registrace uživatelů byla zakázána.');
                $this->redirect('this');
            } else {
                $this->model->getSettings()->insert(array(
                    'select' => 'user',
                    'public' => '0'
                ));
                $this->flashMessage('Registrace uživatelů byla zakázána.');
                $this->redirect('this');
            }
        } else {
            $this->flashMessage('Pro tuto operaci musíte mít práva hlavního administrátora.');
            $this->redirect('this');
        }
    }

}