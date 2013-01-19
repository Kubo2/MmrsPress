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
use Nette\Image;
use Nette\Utils;

class AddFilePresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    protected function createComponentUploadForm() {
        $form = new Form();

        $container = $form->addContainer('foto');
        for ($i = 1; $i <= 5; $i++) {
            $container->addUpload('file' . $i, 'Soubor:');
        }
        $form->addhidden('dir', $this->getParam('select'));
        $form->addhidden('ids', $this->getParam('id'));
        $form->addSubmit('create', 'Nahrát');

        $form->onSuccess[] = callback($this, 'uploadFormSubmitted');

        return $form;
    }

    // přidávání článků do diskuze
    public function uploadFormSubmitted(Form $form) {

        if ($form->values->dir == "news") {
            $dir = "news";
            $redir = $dir;
        } else {
            $dir = "pages";
            $redir = "page";
        }
        $setId = $form->values->ids;
        if (!empty($setId)) {
            $redirect = '?id=' . $form->values->ids;
        } else {
            $redirect = '';
        }
        if (!empty($form->values->dir)) {
            $pole = $_FILES['foto']['name'];
            if (!empty($pole)) {
                $count_array = count(array_filter($pole));
                $cykle = 0;
                foreach ($_FILES["foto"]["error"] as $key => $error) {
                    if (is_file($_FILES["foto"]["tmp_name"][$key])) {
                        $cykle++;
                        $img = array('image/jpeg', 'image/gif', 'image/png');
                        $foto = $_FILES["foto"]["tmp_name"][$key];
                        $type = $_FILES["foto"]["type"][$key];
                        $rename_foto = $this->webalize($_FILES["foto"]["name"][$key]);
                        $isExist = WWW_DIR . "/" . $dir . "/" . $rename_foto;
                        // nastavení velikosti fotek a miniatur
                        $isExistSet = $this->model->getSetImg()->count('*');
                        if ($isExistSet >= 1) {
                            foreach ($this->model->getSetImg() as $newsImg) {
                                $width = $newsImg->newsImg;
                            }
                        } else {
                            $width = 500;
                        }

                        // podpora souborů
                        $podpora = array('application/msword', 'application/pdf', 'image/jpeg',
                            'image/gif', 'image/png', 'application/vnd.oasis.opendocument.text',
                            'application/vnd.ms-excel', 'application/vnd.oasis.opendocument.spreadsheet',
                            'text/plain', 'application/zip','application/vnd.ms-powerpoint');
                        if (in_array($type, $podpora)) {
                            if (file_exists($isExist) == 0) {
                                if (in_array($type, $img)) {
                                    $velikost = getimagesize($_FILES["foto"]["tmp_name"][$key]);
                                    $sirka = $velikost[0];
                                    if ($sirka >= $width) {
                                        $image = Image::fromFile($_FILES["foto"]["tmp_name"][$key]);
                                        $image->resize($width, NULL);
                                        $image->sharpen();
                                        $image->save('./' . $dir . '/' . $rename_foto, 85);
                                    } else {
                                        move_uploaded_file($_FILES["foto"]["tmp_name"][$key], './' . $dir . '/' . $rename_foto);
                                    }
                                    // $this->flashMessage('Obrázek byl přidán.');
                                    if ($count_array == $cykle) {
                                        $this->redirect(':Admin:AddNews:' . $redir . $redirect . '#openDialog');
                                    }
                                } else {
                                    move_uploaded_file($_FILES["foto"]["tmp_name"][$key], "./" . $dir . "/" . $rename_foto);
                                    //$this->flashMessage('Soubor byl přidán');
                                    if ($count_array == $cykle) {
                                        $this->redirect(':Admin:AddNews:' . $redir . $redirect . '#openDialog');
                                    }
                                }
                            } else {
                                $this->flashMessage('Soubor s tímto názvem již existuje');
                                if ($count_array == $cykle) {
                                    $this->redirect(':Admin:AddNews:' . $redir . $redirect . '#openDialog');
                                }
                            }
                        } else {
                            $this->flashMessage('Tento typ souboru není podporován!' . $type);
                            if ($count_array == $cykle) {
                                $this->redirect(':Admin:AddNews:' . $redir . $redirect . '#openDialog');
                            }
                        }
                    }
                }
            } else {
                $this->flashMessage('Přílohu se nepodařilo nahrát. Zkuste to znovu prosím.');
            }
        } else {
            $this->flashMessage('Přílohu se nepodrařilo nahrát. Buď byly posílány příliš velké soubory, 
                nebo nebyla vybraná sekce do které se mají soubory nahrát! Zkuste to znovu prosím.');
            $this->redirect(':Admin:AddNews:news' . $redir . '#openDialog');
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

}