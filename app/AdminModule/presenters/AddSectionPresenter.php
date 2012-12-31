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

class AddSectionPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    public function beforeRender() {
        parent::beforeRender();
        $this->template->section = $this->model->getSection();
    }

    protected function createComponentAddSectionForm() {
        $id = $this->getParam('id');
        if (empty($id)) {
            $value_sekce = '';
        } else {
            $value = $this->model->getSection()->where('id', $id)->fetch();
            $value_sekce = $value->section;
        }
        $form = new Form();

        $form->addText('section', 'Nová rubrika', 30, 40)
                ->setDefaultValue($value_sekce)
                ->addRule(Form::FILLED, 'Nebyl vyplněn text');

        $form->addHidden('id', $id);

        $form->addSubmit('create', 'Uložit');

        $form->onSuccess[] = callback($this, 'sectionFormSubmitted');

        return $form;
    }

    public function sectionFormSubmitted(Form $form) {
        if ($this->getUser()->loggedIn) {
            $user_name = $this->getUser()->getIdentity()->users;
        }
        if (empty($form->values->id)) {
            $this->model->getSection()->insert(array(
                'section' => $form->values->section
            ));
            $this->flashMessage('Rubrika byla přidána.');
            $this->redirect('this');
        } else {
            $this->model->getSection()->where('id', $form->values->id)->update(array(
                'section' => $form->values->section
            ));
            $this->flashMessage('Rubrika byla změněna.');
            $this->redirect('this');
        }
    }

    public function handleDelete($id) {
        $value = base64_decode($this->getParam('id'));
        if ($this->getUser()->loggedIn) {
            if (isset($value)) {
                $this->model->getSection()->where('section', $value)->delete();
                $this->model->getNews()->where('section', $value)->delete();
                $this->flashMessage('Rubrika i její podstránky byly smazány.');
                $this->redirect($this->action);
            }
        } else {
            $this->flashMessage('Na tuto operaci nemáte oprávnění.');
            $this->redirect(':Front:News:news');
        }
    }
    
    public function handlePublic($id) {
        $value = $this->getParam('id');
        if (isset($value)) {
            if ($this->getUser()->loggedIn) {
                if ($this->user_role == 3) {
                    $this->flashMessage('Tato  operace není v demo módu dostupná.');
                    $this->redirect('this');
                } else {
                    $this->model->getSection()->where('id', $id)->update(array(
                        'public' => 1
                    ));
                    $this->flashMessage('Rubrika byla zveřejněna.');
                    $this->redirect($this->action);
                }
            } else {
                $this->flashMessage('Na tuto operaci nemáte oprávnění.');
            }
        }
    }

    public function handlehidden($id) {
        $value = $this->getParam('id');
        if (isset($value)) {
            if ($this->getUser()->loggedIn) {
                $this->model->getSection()->where('id', $id)->update(array(
                    'public' => 0
                ));
                $this->flashMessage('Rubrika byla nasatvena jako skrytá.');
                $this->redirect($this->action);
            } else {
                $this->flashMessage('Na tuto operaci nemáte oprávnění.');
            }
        }
    }

}