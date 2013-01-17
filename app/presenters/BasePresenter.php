<?php

/**
 *
 * @author Dušan Vala as rellik
 * http://mmrspress.eu
 * 
 */
use Nette\Application\UI\Form;
use Nette\Utils\Html;
use Nette\Utils\Paginator;
use Nette\Http\User;
use Nette\Application\UI\Control;
use Nette\Security\IIdentity;
use Nette\Database;

abstract class BasePresenter extends Nette\Application\UI\Presenter {

    protected $model;
    protected $user_role;

    public function startup() {
        parent::startup();

        $this->model = $this->getService('model');

        if ($this->model->isTables() == null) {
            $this->model->getInstall();
            header('Location: ./install_table.php');
            exit();
        }

// user
        if ($this->getUser()->loggedIn) {
            $this->user_role = $this->getUser()->getIdentity()->role;
        } else {
            $this->user_role = false;
        }
// layout base settings
        if ($this->model->getLayout()->count('*') == 0) {
            $this->model->getReset();
        }
    }

    protected function beforeRender() {
        $this->template->userRole = $this->user_role;
        $this->template->viewName = $this->view;
        $this->template->root = dirname(realpath(APP_DIR));

        $a = strrpos($this->name, ':');
        if ($a === FALSE) {
            $this->template->moduleName = '';
            $this->template->presenterName = $this->name;
        } else {
            $this->template->moduleName = substr($this->name, 0, $a + 1);
            $this->template->presenterName = substr($this->name, $a + 1);
        }

// menu admin
        $this->template->menuSettings = array(
            'Aktuální přehled' => ':Admin:Info:',
            'Vzhled' => ':Admin:SetLayout:',
            'Aktuality' => ':Admin:SetNews:',
            'Kontaktní formulář' => ':Admin:ContactForm:',
            'Rss' => ':Admin:Rss:',
            'Média' => ':Admin:SetImages:',
            'Kniha návštěv' => ':Admin:SetBook:',
            'Počitadlo' => ':Admin:SetCounter:',
            'Správa uživatelů' => ':Admin:registration:'
        );

// user role
        if ($this->getUser()->loggedIn) {
            $this->template->user_name = $this->getUser()->getIdentity()->users;
            $this->template->userWiew = $this->getUser()->getIdentity()->role;
        } else {
            $this->template->user_name = false;
            $this->template->userWiew = false;
        }
// menu settings
        $this->template->settingsMenu = $this->model->getSettings()->where('select', 'menu');
        $this->template->settingsNoMenu = $this->model->getSettings()->where('select', 'left_block');
        $top = $this->model->getSettings()->where('select', 'left_block')->fetch();
        if (empty($top->public)) {
            $this->template->menuLeft = 0;
            $left = 0;
        } else {
            $this->template->menuLeft = $top->public;
            $left = $top->public;
        }

        $IfSettingsMenu = $this->model->getSettings()->where('select', 'menu')->fetch();
        if (empty($IfSettingsMenu->public)) {
            $this->template->menu = 0;
        } else {
            $this->template->menu = $IfSettingsMenu->public;
        }

// title

        $titulek = $this->model->getLayout()->limit(1)->fetch();
        if (empty($titulek->title)) {
            $this->template->set_title = 'MmrsPress';
        } else {
            $this->template->set_title = $titulek->title;
        }
        $this->template->description = $titulek->description;



//email admin
        $user = $this->model->getUsers()->where('users', 'Admin')->limit(1)->fetch();
        if (empty($user->email)) {
            $this->template->admin = 'rellik@mmrspress.eu';
        } else {
            $this->template->admin = $user->email;
        }

// pages links
        $this->template->menuPage = $this->model->getNews()->where(array('section' => 'page', 'public' => '1'));
//news
        $date = Date("Y-m-d");
        $this->template->isNews = $this->model->getNews()->where(array('section' => 'news', 'public' => 1, 'publicDate <= ?' => $date));
        $this->template->set_aktual = $this->model->getSettings()->where(array('select' => 'news', 'public' => 1))->count('*');
// settings
        $this->template->settings = $this->model->getSettings();
// settings counter
        $this->template->settings_counter = $this->model->getSettings();
// settings book
        $this->template->set_book = $this->model->getSettings()->where(array('select' => 'book', 'public' => 1))->count("*");
// settings registration
        $this->template->set_registration = $this->model->getSettings()->where(array('select' => 'user', 'public' => 1))->count("*");
// settings layout
        $reset = $this->model->getLayout()->limit(1)->fetch();
        if (empty($reset->id)) {
            $this->template->reset = false;
        } else {
            $this->template->reset = $reset->id;
        }
        $rightBlockSetting = $this->model->getSettings()->where(array('select' => 'right_block', 'public' => '1'))->count('*');
        $this->template->rightBlock = $rightBlockSetting;
        $this->template->css = $this->model->getLayout()->limit(1);
        $cssArray = $this->model->getLayout()->limit(1)->fetch();
//////////////////// Settings web colors /////////////////////////////
        /*         * ************** background logo **************** logo image ********* position logo image ****** */
        $cssArr = '{' . $cssArray->layout . '}{' . $cssArray->logo . '}{' . $cssArray->float . '}';
        /*         * ************** width page ************** background page ************* pading text logo */
        $cssArr .= '{' . $cssArray->width . '}{' . $cssArray->backColor . '}{' . $cssArray->padding . '}';
        /*         * ************** left block **** right block ***************** links *************************** */
        $cssArr .= '{' . $left . '}{' . $rightBlockSetting . '}{' . $cssArray->Acolor . '}';
        /*         * ************** background content *************** links hover ************* layout color * */
        $cssArr .= '{' . $cssArray->ContentColor . '}{' . $cssArray->AcolorH . '}{' . $cssArray->pageColor . '}';
        /*         * ************** color text ****************** headers ******************** footer ************ */
        $cssArr .= '{' . $cssArray->textColor . '}{' . $cssArray->hColor . '}{' . $cssArray->footer . '}';
        /*         * ************** menu ************* menu hover ************************************ */
        $cssArr .= '{' . $cssArray->menuA . '}{' . $cssArray->menuH . '}';
        /*         * ************** photogallery td ************* photogallery td hover *********** photogallery text * */
        $cssArr .= '{' . $cssArray->galleryTd . '}{' . $cssArray->galleryTdH . '}{' . $cssArray->tdColor . '}';

        $this->template->css64 = base64_encode($cssArr);

// settings RSS
        $this->template->setRss = $this->model->getSetRss();
// section pages
        $this->template->rubriky = $this->model->getSection()->where('public','1');
        $this->template->rubriky_menu = $this->model->getNews()->where('public', '1');
        $this->template->rubriky_nazev = '';
        if ($this->getParam('wiew') != '') {
            $setPodmenuWiew = $this->model->getNews()->where('id', $this->getParam('wiew'))->fetch();
            $this->template->podmenu = $setPodmenuWiew->section;
        } else {
            $this->template->podmenu = 'not';
        }

// photogallery
        $this->template->isGallery = $this->model->getGallery()->where('public', 1);
// rand image in menu
        foreach ($this->model->getGallery()->where('public', 1)->order('rand()') as $pthotoImage) {
            $select = array($pthotoImage->folder);
            $this->template->photosMenu = $this->model->getPhotos()->where(array('folder' => $select))->order('rand()')->limit(1);
        }
// facebook
        $this->template->settings_fb = $this->model->getSettings();
// counter
        $isCountAll = $this->model->getCounter_all()->count('*');
        $CountAll = $this->model->getCounter_all()->fetch();
        if ($isCountAll == 0) {
            $this->model->getCounter_all()->insert(array('count' => '0'));
            $this->redirect('this');
        }

        $counterAll = $this->model->getCounter_all()->fetch();
        $this->template->counter_all = $counterAll->count;
// day counter
        $this->template->counter = $this->model->getCounter();

        $cookie = 'counter';
        $time = date("m.d.Y");
        $info = md5($_SERVER["SERVER_ADDR"] . $_SERVER['HTTP_USER_AGENT'] . $_SERVER['HTTP_ACCEPT'] . gethostbyaddr($_SERVER["SERVER_ADDR"]) . $_SERVER['REMOTE_PORT'] . $time);
        $cook = substr(md5(rand()), 0, 5);
        $ifCount = $this->model->getCounter()->where('info', $info)->count('*');
        if (!isset($_COOKIE[$cookie]) AND $ifCount == 0) {
            $this->getHttpResponse()->setCookie($cookie, $cook, 0);
            $this->model->getCounter()->insert(array(
                'info' => $info,
                'date' => new dateTime()
            ));
            $this->model->getCounter_all()->update(array('count' => $CountAll->count + 1));
        }
// daily data erase counter when the data are older than the current date
        $this->model->getCounter()->where('date <> ?', date("Y-m-d"))->delete('*');

        // contact form settings //
        $isFormSet = $this->model->getSettings()->where('select', 'contact')->fetch();
        if ($isFormSet == false) {
            $this->template->contactForm = 0;
        } else {
            $this->template->contactForm = $isFormSet->public;
        }
    }

//searching //
    protected function createComponentSearchForm() {
        $form = new Form();

        $form->addText('search', '', 50, 100)
                ->addRule(Form::FILLED, 'Nebyl vyplněn text')
                ->addRule(Form::MIN_LENGTH, 'Hledaný text musí mít alespoň %d znaky', 4);
        ;
        $form->addSubmit('create', 'Hledat');

        $form->onSuccess[] = callback($this, 'searchFormSubmitted');

        return $form;
    }

    public function searchFormSubmitted(Form $form) {
        $obsah = $form->values->search;
        $obsah = str_replace("š", "&scaron;", $obsah);
        $obsah = str_replace("ý", "&yacute;", $obsah);
        $obsah = str_replace("á", "&aacute;", $obsah);
        $obsah = str_replace("í", "&iacute;", $obsah);
        $obsah = str_replace("é", "&eacute;", $obsah);
        $obsah = str_replace("ó", "&oacute;", $obsah);
        $obsah = str_replace("ú", "&uacute;", $obsah);
        $obsah = str_replace("Š", "&Scaron;", $obsah);
        $obsah = str_replace("Ý", "&Yacute;", $obsah);
        $obsah = str_replace("Á", "&Aacute;", $obsah);
        $obsah = str_replace("Í", "&Iacute;", $obsah);
        $obsah = str_replace("É", "&Eacute;", $obsah);
        $obsah = str_replace("Ó", "&Oacute;", $obsah);
        $obsah = str_replace("Ú", "&Uacute;", $obsah);
        $obsah = str_replace("-", "&ndash;", $obsah);
        $obsah = str_replace("+", " ", $obsah);
        $obsah = str_replace("&", "&amp;", $obsah);

        $this->redirect('Search:default?find=' . base64_encode($obsah));
    }

// contact form //

    protected function createComponentContactForm() {
        $captchaArray = array('Lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipisicing', 'elit', 'eiusmod', 'tempor', 'incididunt', 'labore');
        $cpta = $captchaArray[rand(0, 11)];

        $form = new Form;
        $form->addGroup('Zpráva');
        $form->addText('nick', 'Jméno:', NULL, 15)
                ->setRequired('Jméno musí být vyplněno!');

        $form->addText('email', 'E-mail:')
                ->addRule($form::FILLED, 'Email musí být vyplněn abychom Vám mohli odpovědět.')
                ->addRule($form::EMAIL, 'Nesprávně uvedený email!');

        $form->addText('web', 'Web:')
                ->setEmptyValue('http://')
                ->addCondition($form::FILLED)
                ->addRule($form::URL, 'Nesprávně uvedená adresa webu!');
        $form->addTextArea('text', 'Zpráva', 40, 6);

        $form->addGroup('Ochrana');

        $form->addText('cpta', 'Opište: ' . $cpta, 15)
                ->addRule(Form::FILLED, 'Antispam je nutné vyplnit!');
        $form->addHidden('captcha', $cpta);

        $form->addSubmit('send', 'Odeslat');

        $form->onSuccess[] = callback($this, 'contactFormSubmitted');

        return $form;
    }

    public function contactFormSubmitted($form) {
        if ($form->values->cpta == $form->values->captcha) {
            $isFormSet = $this->model->getSettings()->where('select', 'contact')->fetch();
            if ($isFormSet == false) {
                $user = $this->model->getUsers()->where('users', 'Admin')->limit(1)->fetch();
                if (empty($user->email)) {
                    $email = 'rellik@mmrspress.eu';
                } else {
                    $email = $user->email;
                }
            } else {
                $user = $this->model->getUsers()->where('id', $isFormSet->count)->limit(1)->fetch();
                if (empty($user->email)) {
                    $email = 'rellik@mmrspress.eu';
                } else {
                    $email = $user->email;
                }
            }

            /////////// posílání zpráv ////////////
            $subject = $_SERVER['HTTP_HOST'];
            $message = "Nová zpráva z webového formuláře " . $_SERVER['HTTP_HOST'] . " \n\n" . $form->values->text;
            $headers = 'From:' . $form->values->email . "\r\n" .
                    'Reply-To:' . $form->values->email . "\r\n" .
                    'MIME-Version: 1.0' . "\n" . 'Content-type: text/plain; charset=UTF-8; Content-Transfer-Encoding: 8bit';
            mail($email, $subject, strip_tags($message), $headers);
            //////////////// konec RSS zpráv /////////////

            $this->flashMessage('Zpráva byla odeslána.');
            $this->redirect('News:news');
        } else {
            $this->flashMessage('Špatný potvrzovací kód! Zkuste to znovu prosím.');
        }
    }

}