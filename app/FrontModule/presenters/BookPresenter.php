<?php

namespace FrontModule;

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

class BookPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        $IsBookPublic = $this->model->getSettings()->where(array('select' => 'book', 'public' => 1))->count('*');
        if ($IsBookPublic == 0) {
            $this->redirect('News:news');
        }
    }

    protected function createComponentBookForm() {
        if ($this->getUser()->loggedIn) {
            $user_name = $this->getUser()->getIdentity()->users;
            $user_email = $this->getUser()->getIdentity()->email;
            $user_web = $this->getUser()->getIdentity()->web;
        } else {
            $user_name = "";
            $user_email = "";
            $user_web = "";
        }
        $form = new Form();

        $form->addText('jmeno', 'Jméno:', '20', '20')
                ->setValue($user_name)
                ->addRule(Form::FILLED, 'Zadejte prosím své jméno');

        $form->addText('email', 'E-mail:')
                ->setValue($user_email)
                ->addCondition($form::FILLED)
                ->addRule($form::EMAIL, 'Nesprávně uvedený email!');

        $form->addText('web', 'Web:')
                ->setValue($user_web)
                ->setEmptyValue('http://')
                ->addCondition($form::FILLED)
                ->addRule($form::URL, 'Nesprávně uvedená adresa webu!');

        $form->addTextArea('zprava', 'Vzkaz:', 55, 10)
                ->addRule(Form::FILLED, 'Napište nám prosím vzkaz');

        if (!$this->getUser()->loggedIn) {

            $form->addText('cpta', 'Napište číslo tři:', 5)
                    ->addRule(Form::FILLED, 'Antispam je nutné vyplnit!');
        }

        $form->addSubmit('create', 'Vytvořit');

        $form->onSuccess[] = callback($this, 'bookFormSubmitted');

        return $form;
    }

    public function beforeRender() {
        parent::beforeRender();
        $this->template->userWiew = $this->model->getUsers();

        $vp = new \VisualPaginator($this, 'vp');
        $paginator = $vp->getPaginator();
        $paginator->itemsPerPage = 10;
        $paginator->itemCount = count($this->model->getBook());
        $this->template->tasks = $this->model->getBook()
                        ->order('id DESC')->limit($paginator->itemsPerPage, $paginator->offset);
        require_once LIBS_DIR . '/hightlight.php';
    }

    public function handleDelete($id) {
        $value = $this->getParam('id');
        if (isset($value)) {
            if ($this->getUser()->loggedIn) {
                $this->model->getBook()->where('id', $id)->delete();
                $this->flashMessage('Zpráva byla smazána.');
                $this->redirect('this');
            } else {
                $this->flashMessage('Na tuto operaci nemáte oprávnění.');
                $this->redirect('this');
            }
        }
    }

    public function bookFormSubmitted(Form $form) {
        if (!$this->getUser()->loggedIn) {
            if ($form->values->cpta === '3') {
                $userExist = $this->model->getUsers()->where('users', $form->values->jmeno)->count('*');
                if ($userExist == 0) {
                    $this->model->getBook()->insert(array(
                        'name' => htmlspecialchars($form->values->jmeno),
                        'email' => htmlspecialchars($form->values->email),
                        'web' => htmlspecialchars($form->values->web),
                        'date' => new \DateTime(),
                        'mesage' => htmlspecialchars($form->values->zprava)
                    ));
                    $this->flashMessage('Zpráva byla přidána.');
                    $this->redirect('this');
                } else {
                    $this->flashMessage('Jméno nelze použít, jelikož je přiřazeno některému ze správců.');
                }
            } else {
                $this->flashMessage('Špatně vyplněná ochrana.');
            }
        } else {

            $this->model->getBook()->insert(array(
                'name' => htmlspecialchars($form->values->jmeno),
                'email' => htmlspecialchars($form->values->email),
                'web' => htmlspecialchars($form->values->web),
                'date' => new \DateTime(),
                'mesage' => htmlspecialchars($form->values->zprava),
                'avatar' => $this->getUser()->getIdentity()->avatar
            ));
            $this->flashMessage('Zpráva byla přidána.');
            $this->redirect('this');
        }
    }

}