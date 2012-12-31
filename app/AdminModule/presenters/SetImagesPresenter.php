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

class SetImagesPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    public function beforeRender() {
        parent::beforeRender();
        $this->template->sizeImg = $this->model->getSetImg();
    }

    protected function createComponentSetImgForm() {
        $isExistSet = $this->model->getSetImg()->count('*');
        if($isExistSet != 0) {
            foreach ($this->model->getSetImg() as $galImg) {
                $thumb = $galImg->thumbImg;
                $wiews = $galImg->wiewImg;
                $news = $galImg->newsImg;
            }
        } else {
            $thumb = 150;
            $wiews = 800;
            $news = 500;
        }

        $form = new Form();

        $form->addGroup('Velikost fotografií ve fotogalerii');

        $form->addText('thumbImg', 'Maximální šířka miniatury ve fotogalerii:',3,3)
                ->setValue($thumb)
                ->addRule(Form::INTEGER, 'Velikost musí být číslo')
                ->addRule(Form::RANGE, 'Velikost musí být od %d do %d', array(50, 200));

        $form->addText('wiewImg', 'Maximální šířka původní fotografie:', 4,4)
                ->setValue($wiews)
                ->addRule(Form::INTEGER, 'Velikost musí být číslo')
                ->addRule(Form::RANGE, 'Velikost musí být od %d do %d', array(300, 1024));
        
        $form->addGroup('Velikost obrázkových příloh článků');

        $form->addText('newsImg', 'Maximální šířka obrázkové přílohy:',4,4)
                ->setValue($news)
                ->addRule(Form::INTEGER, 'Velikost musí být číslo')
                ->addRule(Form::RANGE, 'Velikost musí být od %d do %d', array(50, 1024));

        $form->addSubmit('create', 'Uložit');

        $form->onSuccess[] = callback($this, 'setImgFormSubmitted');

        return $form;
    }

    public function setImgFormSubmitted(Form $form) {
        $isExist = $this->model->getSetImg()->count('*');
        if ($isExist == 0) {
            $this->model->getSetImg()->insert(array(
                'thumbImg' => intval($form->values->thumbImg),
                'wiewImg' => intval($form->values->wiewImg),
                'newsImg' => intval($form->values->newsImg)
            ));
            $this->flashMessage('Velikost obrázků byla nastavena.');
            $this->redirect('this');
        } else {
            $this->model->getSetImg()->update(array(
                'thumbImg' => intval($form->values->thumbImg),
                'wiewImg' => intval($form->values->wiewImg),
                'newsImg' => intval($form->values->newsImg)
            ));
            $this->flashMessage('Velikost obrázků byla nastavena.');
            $this->redirect('this');
        }
    }
    
    // nastavení panelu jako veřejné
    public function handleVerejna($id) {
        if (isset($id)) {
            $this->model->getSettings()->where('id', $id)->update(array(
                'public' => '1'
            ));
            $this->flashMessage('Panel byl nastaven jako veřejný.');
            $this->redirect('this');
        } else {
            $this->model->getSettings()->insert(array(
                'select' => 'photoPanel',
                'public' => '1'
            ));
            $this->flashMessage('Panel byl nastaven jako veřejný.');
            $this->redirect('this');
        }
    }

// nastavení panelu jako skryté
    public function handleSkryta($id) {
        if (isset($id)) {
            $this->model->getSettings()->where('id', $id)->update(array(
                'public' => '0'
            ));
            $this->flashMessage('Panel byl nastaven jako skrytý.');
            $this->redirect('this');
        } else {
            $this->model->getSettings()->insert(array(
                'select' => 'photoPanel',
                'public' => '0'
            ));
            $this->flashMessage('Panel byl nastaven jako skrytý.');
            $this->redirect('this');
        }
    }

}