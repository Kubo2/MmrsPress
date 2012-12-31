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

class LabelPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    public function beforeRender() {
        parent::beforeRender();
        $this->template->foto = $this->model->getPhotos()
                        ->where('id', $this->getParam('id'))->limit(1);
    }

    protected function createComponentDescriptionImageForm() {
        $value = $this->getParam('id');
        $folder = $this->getParam('folder');
        if (isset($value)) {
            foreach ($this->model->getPhotos()->where('id', $value) as $gal) {
                $popis = html_entity_decode($gal->label);
            }
        } else {
            $popis = "";
        }

        $form = new Form();
        $form->addText('popis', 'Popis', 50)
                ->setDefaultValue($popis)
                ->addRule(Form::FILLED, 'Název musí být vyplněn');

        $form->addHidden('id', $value);

        $form->addHidden('folder', $folder);

        $form->addSubmit('create', 'Uložit');

        $form->onSuccess[] = callback($this, 'descriptionImageFormSubmitted');

        return $form;
    }

    public function descriptionImageFormSubmitted(Form $form) {
        $this->model->getPhotos()->where('id', $form->values->id)->update(array(
            'label' => htmlspecialchars($form->values->popis)
        ));
        $this->flashMessage('Popis fotografie byl aktualizován.');
        $this->redirect('Photos:default', array('folder' => $form->values->folder));
        
        
    }

}