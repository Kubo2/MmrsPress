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
use Nette\Image;

class InstallPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if ($this->model->getUsers()->where('users', 'Admin')->count('*') == 1) {
            $this->flashMessage('Systém už je nainstalován! Nelze znovu zadat hlavního admina.
                V případě potřeby kontaktujte webmastera.');
            $this->redirect(':Front:Sign:default');
        }
    }

    protected function createComponentNewPassForm() {
        $form = new Form;

        $form->addText('email', 'E-mail:')
                ->addCondition($form::FILLED)
                ->addRule($form::EMAIL, 'Nesprávně uvedený email!');

        $form->addText('web', 'Web:')
                ->setEmptyValue('http://')
                ->addCondition($form::FILLED)
                ->addRule($form::URL, 'Nesprávně uvedená adresa webu!');
        $form->addUpload('avatar', 'Avatar:')
                ->addCondition(Form::IMAGE, 'Avatar musí být JPEG nebo PNG.')
                ->addRule(Form::MAX_FILE_SIZE, 'Maximální velikost souboru je 900 kB.', 900 * 1024 /* v bytech */);

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
        //make a directory
        if (!is_dir('gallery')) {
            mkdir("gallery", 0777);
        }
        if (!is_dir('image')) {
            mkdir("image", 0777);
        }
        if (!is_dir('log')) {
            mkdir("log", 0777);
        }
        if (!is_dir('avatar')) {
            mkdir("avatar", 0777);
        }
        
        $file_type = array("image/jpeg", "image/png");
        if ($this->model->getUsers()->where('users', 'Admin')->count('*') == 0) {
                if (!empty($_FILES["avatar"]["type"])) {
                    if (in_array($_FILES["avatar"]["type"], $file_type)) {
                        $rename_foto = $this->webalize('admin' . $_FILES["avatar"]["name"]);
                        $this->model->getUsers()->insert(array(
                            'users' => 'Admin',
                            'role' => '1',
                            'password' => md5($form->values->password),
                            'email' => $form->values->email,
                            'web' => $form->values->web,
                            'avatar' => $rename_foto,
                            'active' => '1'
                        ));
                        $image_thumb = Image::fromFile($_FILES['avatar']['tmp_name']);
                        $image_thumb->resize(60, 60, Image::SHRINK_ONLY);
                        $image_thumb->sharpen();
                        $image_thumb->save(WWW_DIR . '/avatar/' . $rename_foto, 80);
                        $this->flashMessage('Hlavní administrátor byl úspěšně přidán.');
                        $this->redirect('EmptyPage:');
                    } else {
                        $this->flashMessage('Avatar může být pouze obrázek jpg nebo png!.');
                    }
                } else {
                    $rename_foto = 'admin' . $this->webalize($_FILES["avatar"]["name"]);
                    $this->model->getUsers()->insert(array(
                        'users' => 'Admin',
                        'role' => '1',
                        'password' => md5($form->values->password),
                        'email' => $form->values->email,
                        'web' => $form->values->web,
                        'avatar' => 'avatar.png',
                        'active' => '1'
                    ));
                    $this->flashMessage('Hlavní administrátor byl úspěšně přidán.');
                    $this->redirect('EmptyPage:');
                }
        } else {
            $this->flashMessage('Jméno již je obsazeno. Zdejte nějaké jiné.');
        }
        $this->flashMessage('Instalace byla úspěšně provedena.');
        $this->redirect('EmptyPage:default');
    }
    
    public static function webalize($s, $charlist = NULL, $lower = TRUE) {
        $s = strtr($s, '`\'"^~', '-----');
        if (ICONV_IMPL === 'glibc') {
            $s = @iconv('UTF-8', 'WINDOWS-1250//TRANSLIT', $s); // intentionally @
            $s = strtr($s, "\xa5\xa3\xbc\x8c\xa7\x8a\xaa\x8d\x8f\x8e\xaf\xb9\xb3\xbe\x9c\x9a\xba\x9d\x9f\x9e\xbf\xc0\xc1\xc2\xc3\xc4\xc5\xc6\xc7\xc8\xc9\xca\xcb\xcc\xcd\xce\xcf\xd0\xd1\xd2"
                    . "\xd3\xd4\xd5\xd6\xd7\xd8\xd9\xda\xdb\xdc\xdd\xde\xdf\xe0\xe1\xe2\xe3\xe4\xe5\xe6\xe7\xe8\xe9\xea\xeb\xec\xed\xee\xef\xf0\xf1\xf2\xf3\xf4\xf5\xf6\xf8\xf9\xfa\xfb\xfc\xfd\xfe", "ALLSSSSTZZZallssstzzzRAAAALCCCEEEEIIDDNNOOOOxRUUUUYTsraaaalccceeeeiiddnnooooruuuuyt");
        } else {
            $s = @iconv('UTF-8', 'ASCII//TRANSLIT', $s); // intentionally @
        }
        $s = str_replace(array('`', "'", '"', '^', '~'), '', $s);
        if ($lower) {
            $s = strtolower($s);
        }
        $s = str_replace(' ', '_', $s);
        $s = trim($s, '_');
        return $s;
    }

}