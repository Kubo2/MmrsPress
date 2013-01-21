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

class UploaderPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    public function beforeRender() {
        parent::beforeRender();
        $this->template->galerie = $this->model->getGallery()
                        ->where('folder', $this->getParam('folder'))->limit(1);
    }

    protected function createComponentUploadForm() {
        $form = new Form();
        $container = $form->addContainer('foto');
        for ($i = 1; $i <= 5; $i++) {
            $container->addUpload('file' . $i, 'Soubor:');
            $container->addText('label' . $i, 'Popis');
        }
        $folder = $this->getParam('folder');
        $form->addHidden('folder', $folder);
        $form->addSubmit('create', 'Nahrát');
        $form->onSuccess[] = callback($this, 'uploadFormSubmitted');
        return $form;
    }

    public function uploadFormSubmitted(Form $form) {
        if (!empty($form->values->folder)) {
            $pole = $_FILES['foto']['name'];
            if (!empty($pole)) {
                $count_array = count(array_filter($pole));
                $cykle = 0;
                foreach ($_FILES["foto"]["error"] as $key => $error) {
                    if (is_file($_FILES["foto"]["tmp_name"][$key])) {
                        $cykle++;
                        //$label = $form->values->foto['label' . $cykle];
                        $file_type = array("image/jpeg", "image/png");
                        if (in_array($_FILES["foto"]["type"][$key], $file_type)) {
                            $size = getimagesize($_FILES["foto"]["tmp_name"][$key]);
                            $width = $size[0];
                            $height = $size[1];
                            $rename_foto = $this->webalize($_FILES["foto"]["name"][$key]);
                            // nastavení velikosti fotek a miniatur - získání údajů z DB
                            $isExistSet = $this->model->getSetImg()->count('*');
                            if ($isExistSet >= 1) {
                                foreach ($this->model->getSetImg() as $galImg) {
                                    $thumb = $galImg->thumbImg;
                                    $wiews = $galImg->wiewImg;
                                }
                            } else { // pokud v DB není nastaveno, použijí se výchozí hodnoty
                                $thumb = 150;
                                $wiews = 800;
                            }
                            // zjištění zda již galerii neexistuje fotka se stejným názvem
                            $isExist = $this->model->getPhotos()->where(array('photo' => $rename_foto, 'folder' => $form->values->folder))->count('*');
                            $galleryName = $this->model->getGallery()->where('folder',$form->values->folder)->fetch();
                            if ($isExist == 0) {
                                $this->model->getPhotos()->insert(array(
                                    'photo' => $rename_foto,
                                    'folder' => $form->values->folder,
                                    'label' => $form->values->foto['label' . $cykle]
                                ));
                                // vytvoření miniatur a zmenšení fotek
                                if ($width >= $wiews or $height >= $wiews) {
                                    $image = Image::fromFile($_FILES["foto"]["tmp_name"][$key]);
                                    if ($width > $height) {
                                        $image->resize($wiews, NULL);
                                    } else {
                                        $image->resize(NULL, $wiews);
                                    }
                                    $image->sharpen();
                                    $image->save(WWW_DIR . '/gallery/' . $form->values->folder . '/' . $rename_foto, 100);
                                    $imgThumb = WWW_DIR . '/gallery/' . $form->values->folder . '/' . $rename_foto;
                                    $image_thumb = Image::fromFile($imgThumb);
                                    $image_thumb->resize($thumb, NULL);
                                    $image_thumb->save(WWW_DIR . '/gallery/' . $form->values->folder . '/nahledy/' . $rename_foto, 100);
                                } else {
                                    move_uploaded_file($_FILES["foto"]["tmp_name"][$key], './gallery/' . $form->values->folder . '/' . $rename_foto);
                                    $imgThumb = WWW_DIR . '/gallery/' . $form->values->folder . '/' . $rename_foto;
                                    $image_thumb = Image::fromFile($imgThumb);
                                    $image_thumb->resize($thumb, NULL);
                                    $image_thumb->save(WWW_DIR . '/gallery/' . $form->values->folder . '/nahledy/' . $rename_foto, 100);
                                }
                                $this->flashMessage('Fotografie "' . $_FILES["foto"]["name"][$key] . '" byla přidána.');
                                if ($count_array == $cykle) {
                                    $this->redirect('this', array('folder' => $form->values->folder));
                                }
                            } else {
                                $this->flashMessage('Fotografie "' . $_FILES["foto"]["name"][$key] . '" již v galerii existuje.');
                                if ($count_array == $cykle) {
                                    $this->redirect('this', array('folder' => $form->values->folder));
                                }
                            }
                        } else {
                            $this->flashMessage('Nepodporovaný soubor ("' . $_FILES["foto"]["type"][$key] . '")! Podporovány jsou pouze obrázky jpg nebo png.');
                            if ($count_array == $cykle) {
                                $this->redirect('this', array('folder' => $form->values->folder));
                            }
                        }
                    }
                }
            } else {
                $this->flashMessage('Fotografie se nepodařilo nahrát. Zkuste to znovu prosím.');
            }
        } else {
            $this->flashMessage('Fotografie se nepodrařilo nahrát. Buď byly posílány příliš velké fotografie, 
                nebo nebyla vybraná galerie do které se mají fotografie nahrát! Zkuste to znovu prosím.');
            $this->redirect('SetGallery:default');
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