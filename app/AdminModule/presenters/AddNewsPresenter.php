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

class AddNewsPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    public function beforeRender() {
        parent::beforeRender();
        $vp = new \VisualPaginator($this, 'vp');
        $paginator = $vp->getPaginator();
        $paginator->itemsPerPage = 10;
        $items = $this->model->getNews()->where('section', 'news');
        $paginator->itemCount = count($items);
        $this->template->pages = $this->model->getNews()
                ->where('section', 'news')->order('date DESC')
                ->limit($paginator->itemsPerPage, $paginator->offset);

        // výpis souborů
        if ($this->action == "news") {
            $dir = "news";
        } else {
            $dir = "pages";
        }
        $slozka = WWW_DIR . '/' . $dir . '/';
        if (!is_dir($slozka)) {
            mkdir(WWW_DIR . '/' . $dir . '/', 0777);
        }
        $this->template->id = $this->getParam('id');
        $this->template->images = array();
        $this->template->doc = array();
        $vypis = opendir($slozka);
        while (false !== ($file = readdir($vypis))) {
            if (!is_dir($slozka . $file)) {
                $finfo = explode('.', $file);
                $fileSuffix = array_pop($finfo);
                $array = array("jpg", "png", "gif", "jpeg");
                if (in_array($fileSuffix, $array)) {
                    $this->template->images[] = $file;
                } else {
                    $this->template->doc[] = $file;
                }
            }
        }
        closedir($vypis);
    }

    protected function createComponentAddNewsForm() {
        $publicDate = NULL;
        $form = new Form();

        $id = $this->getParam('id');
        if (empty($id)) {
            $menu = '';
            $obsah = '';
            $id = '';
            $section = $this->action;
        } else {
            foreach ($this->model->getNews()->where('id', $id) as $values) {
                $menu = $values->menu;
                $obsah = html_entity_decode($values->content);
                $section = $values->section;
                $publicDate = substr($values->publicDate, 0, 10);
            }
        }

        $form->addText('menu', 'Název:', 20, 20)
                ->setValue($menu)
                ->setEmptyValue('Vyplňte odkaz')
                ->addRule(Form::FILLED, 'Odkaz musí být vyplněn');
        if($this->action == 'news') {
           $form->addText('publicDate', 'Datum zveřejnění:', 15, 15)
                ->setDefaultValue($publicDate); 
        } else {
            $form->addHidden('publicDate', '');
        }
        

        foreach ($this->model->getSection()->where('public', '1') as $sections) {
            $num[] = array($sections->section => $sections->section);
        }

        if (empty($num)) {
            $num[] = "Stránka";
        } else {
            $num[] .= 'Stránka';
        }


        $num = array_reverse($num);

        if ($this->action != "news") {
            $form->addSelect('section', 'Rubrika:', $num);
            $form->addCheckbox('edit_rubr', 'Změnit rubriku (pouze při editaci)');
        } else {
            $form->addHidden('section', $section);
            $form->addHidden('edit_rubr', '0');
        }

        $form->addTextArea('obsah', 'Obsah', 30, 40)
                ->setValue($obsah)
                ->addRule(Form::FILLED, 'Nebyl vyplněn text')
                ->setHtmlId('mceEditor');

        $form->addHidden('id', $id);

        $form->addSubmit('create', 'Uložit');

        $form->getElementPrototype()->onsubmit('tinyMCE.triggerSave()');

        $form->onSuccess[] = callback($this, 'addNewsFormSubmitted');

        return $form;
    }

    public function addNewsFormSubmitted(Form $form) {
        $section_value = $form->values->section;
        $section_value = str_replace('0', 'page', $section_value);
        if ($this->getUser()->loggedIn) {
            $user_name = $this->getUser()->getIdentity()->users;
        }
        if (empty($form->values->publicDate)) {
            $publicDate = new \DateTime();
        } else {
            $publicDate = $form->values->publicDate;     
        }
        if (empty($form->values->id)) {
            $this->model->getNews()->insert(array(
                'date' => new \DateTime(),
                'publicDate' => $publicDate,
                'content' => htmlspecialchars($form->values->obsah),
                'menu' => htmlspecialchars($form->values->menu),
                'autor' => $user_name,
                'section' => $section_value
            ));
            /////////// posílání zpráv ////////////
            foreach ($this->model->getUsers()->where('role', '1') as $admin) {
                $subject = $_SERVER['HTTP_HOST'];
                $message = "Nový článek na webu s názvem - " . $form->values->menu . "\n\n" . $_SERVER['HTTP_HOST'] . " \n";
                $headers = 'From:' . $admin->email . "\r\n" .
                        'Reply-To:' . $admin->email . "\r\n" .
                        'MIME-Version: 1.0' . "\n" . 'Content-type: text/plain; charset=UTF-8; Content-Transfer-Encoding: 8bit';
                mail($admin->email, $subject, strip_tags($message), $headers);
            }
            //////////////// konec RSS zpráv /////////////
            $this->flashMessage('Zpráva byla přidána.');
            $this->redirect(':Admin:Info:');
        } else {
            if ($form->values->edit_rubr == '1') {
                $this->model->getNews()->where('id', $form->values->id)->update(array(
                    'publicDate' => $publicDate,
                    'content' => htmlspecialchars($form->values->obsah),
                    'menu' => htmlspecialchars($form->values->menu),
                    'section' => $section_value
                ));
                $this->flashMessage('Zpráva byla aktualizována.');
                $this->redirect(':Admin:Info:');
            } else {
                $this->model->getNews()->where('id', $form->values->id)->update(array(
                    'publicDate' => $publicDate,
                    'content' => htmlspecialchars($form->values->obsah),
                    'menu' => htmlspecialchars($form->values->menu)
                ));
                $this->flashMessage('Zpráva byla aktualizována.');
                $this->redirect(':Admin:Info:');
            }
        }
    }

    // správa aktualit


    public function handlePublic($id) {
        $value = $this->getParam('id');
        if (isset($value)) {
            if ($this->getUser()->loggedIn) {
                if ($this->user_role == 3) {
                    $this->flashMessage('Tato  operace není v demo módu dostupná.');
                    $this->redirect('this');
                } else {
                    $this->model->getNews()->where('id', $id)->update(array(
                        'public' => 1,
                        'date' => new \DateTime()
                    ));
                    $this->flashMessage('Aktualita byla zveřejněna.');
                    $this->redirect($this->action);
                }
            } else {
                $this->flashMessage('Na tuto operaci nemáte oprávnění.');
            }
        }
    }

    public function handleHidden($id) {
        $value = $this->getParam('id');
        if (isset($value)) {
            if ($this->getUser()->loggedIn) {
                $this->model->getNews()->where('id', $id)->update(array(
                    'public' => 0,
                    'date' => new \DateTime()
                ));
                $this->flashMessage('Aktualita byla nastavena jako skrytá.');
                $this->redirect($this->action);
            } else {
                $this->flashMessage('Na tuto operaci nemáte oprávnění.');
            }
        }
    }

    public function handleDelete($id) {
        $value = $this->getParam('id');
        if ($this->getUser()->loggedIn) {
            if (isset($value)) {
                $this->model->getNews()->where('id', $id)->delete();
                $this->flashMessage('Zpráva byla smazána.');
                $this->redirect($this->action);
            }
        } else {
            $this->flashMessage('Na tuto operaci nemáte oprávnění.');
            $this->redirect(':Front:News:news');
        }
    }

    public function handleDeleteImg($image) {
        $value = $this->getParam('image');
        if ($this->action == "news") {
            $dir = "news";
        } else {
            $dir = "pages";
        }
        if ($this->getUser()->loggedIn) {
            if (isset($value)) {
                if (file_exists(WWW_DIR . '/' . $dir . '/' . $image)) {
                    unlink(WWW_DIR . '/' . $dir . '/' . $image);
                    $this->redirect($this->action);
                } else {
                    $this->flashMessage('Soubor nelze smazat protože neexistuje.');
                }
            }
        } else {
            $this->flashMessage('Na tuto operaci nemáte oprávnění.');
            $this->redirect(':Front:News:news');
        }
    }

}