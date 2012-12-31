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

class SearchPresenter extends \BasePresenter {

    public function renderDefault() {
        $finds = base64_decode($this->getParam('find'));
        $this->template->finds = $this->model->getNews()->where(array('content LIKE ?' => '%' . $finds . '%', 'public ' => '1'));
        $finds = str_replace('&amp;', '&', $finds);
        $finds = str_replace("&scaron;", "š", $finds);
        $finds = str_replace("&yacute;", "ý", $finds);
        $finds = str_replace("&aacute;", "á", $finds);
        $finds = str_replace("&iacute;", "í", $finds);
        $finds = str_replace("&eacute;", "é", $finds);
        $finds = str_replace("&oacute;", "ó", $finds);
        $finds = str_replace("&uacute;", "ú", $finds);
        $finds = str_replace("&Scaron;", "Š", $finds);
        $finds = str_replace("&Yacute;", "Ý", $finds);
        $finds = str_replace("&Aacute;", "Á", $finds);
        $finds = str_replace("&Iacute;", "Í", $finds);
        $finds = str_replace("&Eacute;", "É", $finds);
        $finds = str_replace("&Oacute;", "Ó", $finds);
        $finds = str_replace("&Uacute;", "Ú", $finds);
        $finds = str_replace("&nbsp;", " ", $finds);
        $finds = str_replace("&ndash;", "-", $finds);
        $this->template->findText = htmlspecialchars($finds);
    }

}