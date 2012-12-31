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

class SetGalleryPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    protected function createComponentAddGalleryForm() {
        $form = new Form();
        $form->addText('galerie', 'Název nové galerie', 35, 40)
                ->addRule(Form::FILLED, 'Název musí být vyplněn');
        $form->addTextArea('popis', 'Popis galerie:', 40, 5);
        $form->addSubmit('create', 'Vytvořit');
        $form->onSuccess[] = callback($this, 'addGalleryFormSubmitted');
        return $form;
    }

    public function beforeRender() {
        parent::beforeRender();
        $vp = new \VisualPaginator($this, 'vp');
        $paginator = $vp->getPaginator();
        $paginator->itemsPerPage = 10;
        $items = $this->model->getGallery();
        $paginator->itemCount = count($items);
        $this->template->tasks = $this->model->getGallery()
                        ->order('id DESC')->limit($paginator->itemsPerPage, $paginator->offset);
        require_once LIBS_DIR . '/galleryFunction.php';
    }

    /*     * *********** Funkce na mazání galerie ******************** */

    public function handleDelete($id) {
        $value = $this->getParam('id');

        function rmdirtree($dirname) {
            if (is_dir($dirname)) {
                $result = array();
                if (substr($dirname, -1) != '/') {
                    $dirname.='/';
                }
                $handle = opendir($dirname);
                while (false !== ($file = readdir($handle))) {
                    if ($file != '.' && $file != '..') {
                        $path = $dirname . $file;
                        if (is_dir($path)) {
                            $result = array_merge($result, rmdirtree($path));
                        } else {
                            unlink($path);
                            $result[].=$path;
                        }
                    }
                }
                closedir($handle);
                rmdir($dirname);
                $result[].=$dirname;
                return $result;
            } else {
                return false;
            }
        }

        foreach ($this->model->getGallery()->where('id', $value) as $del) {
            $delFolder = $del->folder;
        }
        rmdirtree(WWW_DIR.'/gallery/' . $del->folder);

        if (isset($value)) {
            $this->model->getGallery()->where('id', $id)->delete();
            $this->model->getPhotos()->where('folder', $delFolder)->delete();
            $this->flashMessage('Galerie byla smazána.');
            $this->redirect($this->action);
        }
    }

// nastavení galerie jako veřejné
    public function handleVerejna($id) {
        if (isset($id)) {
            $this->model->getGallery()->where('id', $id)->update(array(
                'public' => '1'
            ));
            $this->flashMessage('Galerie byla nastavena jako veřejná.');
            $this->redirect('this');
        }
    }

// nastavení galerie jako neveřejné
    public function handleSkryta($id) {
        if (isset($id)) {
            $this->model->getGallery()->where('id', $id)->update(array(
                'public' => '0'
            ));
            $this->flashMessage('Galerie byla nastavena jako neveřejná.');
            $this->redirect('this');
        }
    }

    public function addGalleryFormSubmitted(Form $form) {
        $slozka = md5($form->values->galerie);
        $isExist = $this->model->getGallery()->where('folder', $slozka)->count('*');
        if ($isExist == 0) {
            require_once LIBS_DIR . '/galleryFunction.php';
            addFolder($slozka);
            $this->model->getGallery()->insert(array(
                'name' => htmlspecialchars($form->values->galerie),
                'label' => htmlspecialchars($form->values->popis),
                'folder' => $slozka,
                'autor' => $this->getUser()->getIdentity()->users
            ));
            $this->flashMessage('Galerie byla přidána.');
            $this->redirect($this->action);
        } else {
            $this->flashMessage('Galerie s tímto názvem již existuje.');
            $this->redirect($this->action);
        }
    }

}