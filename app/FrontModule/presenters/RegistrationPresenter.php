<?php

namespace FrontModule;

/**
 * Description of Registration
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */
use Nette\Application\UI\Form;
use Nette\Application\UI\Control;
use Nette\Http\User;
use Nette\Image;
use Nette\Utils;

class RegistrationPresenter extends \BasePresenter {

// new user
    protected function createComponentNewUserForm() {
        $captchaArray = array('Lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipisicing', 'elit', 'eiusmod', 'tempor', 'incididunt', 'labore');
        $cpta = $captchaArray[rand(0, 11)];

        $form = new Form;
        $form->addGroup('Nový uživatel');
        $form->addText('nick', 'Jméno:', NULL, 15)
                ->setRequired('Zvolte si přihlašovací jméno')
                ->addRule(Form::MIN_LENGTH, 'Jméno musí mít alespoň %d znaky', 4);

        $form->addText('email', 'E-mail:')
                ->addRule($form::FILLED, 'Email musí být vyplněn!')
                ->addRule($form::EMAIL, 'Nesprávně uvedený email!');

        $form->addUpload('avatar', 'Avatar:')
                ->addCondition(Form::IMAGE, 'Avatar musí být JPEG nebo PNG.')
                ->addRule(Form::MAX_FILE_SIZE, 'Maximální velikost souboru je 900 kB.', 900 * 1024 /* v bytech */);

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

        $form->addGroup('Podmínky');

        $form->addCheckbox('verify', 'Souhlasím s podmínkami')
                ->addRule(Form::FILLED, 'Pro dokončení registrace musíte souhlasit s podmnkami!');

        $form->addGroup('Ochrana');

        $form->addText('cpta', 'Opište: ' . $cpta, 15)
                ->addRule(Form::FILLED, 'Antispam je nutné vyplnit!');
        $form->addHidden('captcha', $cpta);

        $form->addSubmit('send', 'Registrovat');

        $form->onSuccess[] = callback($this, 'newUserFormSubmitted');

        return $form;
    }

    public function newUserFormSubmitted($form) {
        $file_type = array("image/jpeg", "image/png");
        $token = strtolower(strtoupper(substr(md5(rand()), 0, 32)));
        if ($this->model->getUsers()->where('users', $form->values->nick)->count('*') == 0 AND $form->values->verify == 1) {
            if ($form->values->cpta == $form->values->captcha) {
                if (!empty($_FILES["avatar"]["type"])) {
                    if (in_array($_FILES["avatar"]["type"], $file_type)) {
                        $rename_foto = $this->webalize($form->values->nick . $_FILES["avatar"]["name"]);
                        $this->model->getUsers()->insert(array(
                            'users' => $form->values->nick,
                            'role' => '2',
                            'password' => md5($form->values->password),
                            'email' => $form->values->email,
                            'web' => $form->values->web,
                            'avatar' => $rename_foto,
                            'active' => $token
                        ));
                        $image_thumb = Image::fromFile($_FILES['avatar']['tmp_name']);
                        $image_thumb->resize(60, 60, Image::SHRINK_ONLY);
                        $image_thumb->sharpen();
                        $image_thumb->save(WWW_DIR . '/avatar/' . $rename_foto, 80);
                        /////////// posílání zpráv ////////////
                        $subject = $_SERVER['HTTP_HOST'];
                        $message = "Registrace - na " . $_SERVER['HTTP_HOST'] . " \n
                        Dokončení registrace provedete následujícím odkazem: \n
                        http://" . $_SERVER['HTTP_HOST'] . "/registration/default?active=" . $token . "&do=set";
                        $headers = 'From:' . $form->values->email . "\r\n" .
                                'Reply-To:' . $form->values->email . "\r\n" .
                                'MIME-Version: 1.0' . "\n" . 'Content-type: text/plain; charset=UTF-8; Content-Transfer-Encoding: 8bit';
                        mail($form->values->email, $subject, strip_tags($message), $headers);
                        //////////////// konec RSS zpráv /////////////

                        $this->flashMessage('Registrace byla úspěšně provedena. Pro aktivaci účtu navštivte svoji emailovou schránku.');
                        $this->redirect('this');
                    } else {
                        $this->flashMessage('Avatar může být pouze obrázek jpg nebo png!.');
                    }
                } else {
                    $rename_foto = $form->values->nick . $this->webalize($_FILES["avatar"]["name"]);
                    $this->model->getUsers()->insert(array(
                        'users' => $form->values->nick,
                        'role' => '2',
                        'password' => md5($form->values->password),
                        'email' => $form->values->email,
                        'web' => $form->values->web,
                        'avatar' => 'avatar.png',
                        'active' => $token
                    ));
                    /////////// posílání zpráv ////////////
                    $subject = $_SERVER['HTTP_HOST'];
                    $message = "Registrace - na " . $_SERVER['HTTP_HOST'] . " \n
                        Dokončení registrace provedete následujícím odkazem: \n
                        http://" . $_SERVER['HTTP_HOST'] . "/registration/default?active=" . $token . "&do=set";
                    $headers = 'From:' . $form->values->email . "\r\n" .
                            'Reply-To:' . $form->values->email . "\r\n" .
                            'MIME-Version: 1.0' . "\n" . 'Content-type: text/plain; charset=UTF-8; Content-Transfer-Encoding: 8bit';
                    mail($form->values->email, $subject, strip_tags($message), $headers);
                    //////////////// konec RSS zpráv /////////////
                    $this->flashMessage('Registrace byla úspěšně provedena. Pro aktivaci účtu navštivte svoji emailovou schránku.');
                    $this->redirect('this');
                }
            } else {
                $this->flashMessage('Špatný potvrzovací kód! Zkuste to znovu prosím.');
            }
        } else {
            $this->flashMessage('Jméno již je obsazeno. Zdejte nějaké jiné.');
        }
    }

    // EDIT user
    protected function createComponentEditUserForm() {
        $captchaArray = array('Lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipisicing', 'elit', 'eiusmod', 'tempor', 'incididunt', 'labore');
        $cpta = $captchaArray[rand(0, 11)];

        $form = new Form;
        $form->addGroup('Editace profilu');
        $form->addText('nick', 'Jméno:', NULL, 15)
                ->setDefaultValue($this->getUser()->getIdentity()->users)
                ->setRequired('Zvolte si přihlašovací jméno')
                ->addRule(Form::MIN_LENGTH, 'Jméno musí mít alespoň %d znaky', 4);

        $form->addText('email', 'E-mail:')
                ->setDefaultValue($this->getUser()->getIdentity()->email)
                ->addRule($form::FILLED, 'Email musí být vyplněn!')
                ->addRule($form::EMAIL, 'Nesprávně uvedený email!');

        $form->addUpload('avatar', 'Avatar:')
                ->addCondition(Form::IMAGE, 'Avatar musí být JPEG nebo PNG.')
                ->addRule(Form::MAX_FILE_SIZE, 'Maximální velikost souboru je 1500 kB.', 1500 * 1024 /* v bytech */);

        $form->addText('web', 'Web:')
                ->setDefaultValue($this->getUser()->getIdentity()->web)
                ->setEmptyValue('http://')
                ->addCondition($form::FILLED)
                ->addRule($form::URL, 'Nesprávně uvedená adresa webu!');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = callback($this, 'editUserFormSubmitted');

        return $form;
    }

    public function editUserFormSubmitted($form) {
        $file_type = array("image/jpeg", "image/png");
        $this->model->getUsers()->where('users', $this->getUser()->getIdentity()->users)->update(array(
            'email' => $form->values->email,
            'web' => $form->values->web,
        ));
        if (!empty($_FILES["avatar"]["type"])) {
            if (empty($user->identity->avatar)) {
                $avatar = $this->getUser()->getIdentity()->users . $this->webalize($_FILES["avatar"]["name"]);
            } else {
                $avatar = $this->getUser()->getIdentity()->avatar;
            }
        } else {
            $avatar = NULL;
        }
        if (!empty($_FILES["avatar"]["type"])) {
            if (in_array($_FILES["avatar"]["type"], $file_type)) {
                $image_thumb = Image::fromFile($_FILES['avatar']['tmp_name']);
                $image_thumb->resize(60, 60, Image::SHRINK_ONLY);
                $image_thumb->sharpen();
                $image_thumb->save(WWW_DIR . '/avatar/' . $avatar, 80);
                $this->model->getUsers()->where('users', $this->getUser()->getIdentity()->users)->update(array(
                    'avatar' => $avatar,
                ));
                $this->flashMessage('Profil byl změněn. Některé změny se projeví až po novém přihlášení.');
                $this->redirect('MyInfo:');
            } else {
                $this->flashMessage('Avatar může být pouze obrázek jpg nebo png!.');
                $this->redirect('MyInfo:');
            }
        }
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

    public function handleDelete($id) {
        if ($this->getUser()->getIdentity()->users != 'Admin') {
            if (file_exists(WWW_DIR . '/avatar/' . $this->getUser()->getIdentity()->avatar)) {
                unlink(WWW_DIR . '/avatar/' . $this->getUser()->getIdentity()->avatar);
            }
            $gallery = $this->model->getGallery()->where('autor', $this->getUser()->getIdentity()->users);

            function rmdirTree($dirname) {
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

            foreach ($gallery as $delGallery) {
                rmdirTree(WWW_DIR . '/gallery/' . $delGallery->folder);
                $this->model->getPhotos()->where('folder', $delGallery->folder)->delete();
            }
            $this->model->getGallery()->where('autor', $this->getUser()->getIdentity()->users)->delete();
            $this->model->getUsers()->where('users', $this->getUser()->getIdentity()->users)->delete();
            $this->model->getNews()->where('autor', $this->getUser()->getIdentity()->users)->delete();
            $this->flashMessage('Uživatelský účet byl odstraněn.');
            $this->redirect('Sign:out');
        } else {
            $this->flashMessage('Hlavního administrátora nelze odstranit!');
            $this->redirect('MyInfo:default');
        }
    }

    public function handleSet($active) {
        $value = $this->getParam('active');
        if (isset($value)) {
            $this->model->getUsers()->where('active', $active)->update(array(
                'active' => 1
            ));
            $this->flashMessage('Váš účet byl aktivován a můžete se přihlásit.');
            $this->redirect('Sign:');
        }
    }

}