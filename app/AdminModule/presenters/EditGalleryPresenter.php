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

class EditGalleryPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    protected function createComponentEditGalleryForm() {
        $value = $this->getParam('id');
        if (isset($value)) {
            foreach ($this->model->getGallery()->where('id', $value) as $gal) {
                $setGalerie = $gal->name;
                $setGaleriePopis = $gal->label;
            }
        } else {
            $setGalerie = '';
            $setGaleriePopis = '';
        }

        $form = new Form();
        $form->addText('galerie', 'Název galerie')
                ->setDefaultValue($setGalerie)
                ->addRule(Form::FILLED, 'Název musí být vyplněn');

        $form->addTextArea('popis', 'Popis galerie:', 40, 5)
                ->setDefaultValue($setGaleriePopis);

        $form->addHidden('id', $value);

        $form->addSubmit('create', 'Uložit');

        $form->onSuccess[] = callback($this, 'editGalleryFormSubmitted');

        return $form;
    }

    public function editGalleryFormSubmitted(Form $form) {
        $this->model->getGallery()->where('id', $form->values->id)->update(array(
            'name' => htmlspecialchars($form->values->galerie),
            'label' => htmlspecialchars($form->values->popis),
            'autor' => $user_name
        ));
        $this->flashMessage('Název galerie byl aktualizován.');
        $this->redirect('SetGallery:');
    }

}