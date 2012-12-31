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

class SetLayoutPresenter extends \BasePresenter {

    public function startup() {
        parent::startup();
        if (!$this->getUser()->loggedIn) {
            $this->flashMessage('Přihlašte se, prosím.');
            $this->redirect(':Front:Sign:default');
        }
    }

    public function beforeRender() {
        parent::beforeRender();
    }

    protected function createComponentSetLayoutForm() {
        $cssArray = $this->model->getLayout()->limit(1)->fetch();
        for ($i = 10; $i <= 200; $i = $i + 10) {
            $num[$i] = $i;
        }
        $backgroundRepeat = array(
            'black' => 'Černobílý',
            'grey' => 'Šedý',
            'blue' => 'Modrý',
            'green' => 'Zelený',
            'red' => 'Červený',
            'brown' => 'Hnědý',
            'limet' => 'Limetka',
            'purple' => 'Fialový',
            'opacity' => 'Přechod'
        );
        $backgroundImage = array(
            'no' => 'Nic',
            'grass' => 'Tráva',
            'list' => 'Listí',
            'circle' => 'Kruhy',
            'cube' => 'Kostky',
            'mmrs' => 'MmrsPress',
        );
        $float = array(
            'l' => 'Vlevo',
            'r' => 'Vpravo',
            'rp' => 'Opakovat',
        );
        $width = array(
            '99%' => '99%',
            '90%' => '90%',
            '80%' => '80%',
            '70%' => '70%',
            '920px' => '920px',
            '900px' => '900px',
            '850px' => '850px',
            '800px' => '800px'
        );

        $form = new Form();

        $form->addGroup('Nastavení loga stránky | Šířka stránky');
        $form->addText('title', 'Titulek stránky:', 50, 50)
                ->setDefaultValue($cssArray->title)
                ->setRequired('Titulek nebyl vyplněn');
        $form->addText('description', 'Popis webu (description):', 50, 250)
                ->setDefaultValue($cssArray->description);
        $form->addSelect('padding', 'Odsazení titulku z levé strany: ', $num)
                ->setDefaultValue($cssArray->padding);
        $form->addSelect('layout', 'Výběr pozadí loga: ', $backgroundRepeat)
                ->setDefaultValue($cssArray->layout)
                ->addRule(form::FILLED, "Není vybrán vzhled!");
        $form->addSelect('width', 'Šířka stránky: ', $width)
                ->setDefaultValue($cssArray->width)
                ->addRule(form::FILLED, "Není vybrán vzhled!");
        $form->addSelect('logo', 'Výběr loga: ', $backgroundImage)
                ->setDefaultValue($cssArray->logo)
                ->addRule(form::FILLED, "Není vybráno logo!");
        $form->addRadioList('float', 'Umístění loga:', $float)
                ->setDefaultValue($cssArray->float)
                ->getSeparatorPrototype()->setName(NULL);

        $form->addGroup('Nastavení barev stránky');
        $form->addText('color', 'Pozadí stránky', 6, 6)
                ->setAttribute('class', 'colorPicker')
                ->setDefaultValue($cssArray->backColor);
        $form->addText('textColor', 'Text: ', 6, 6)
                ->setAttribute('class', 'colorPicker')
                ->setDefaultValue($cssArray->textColor);
        $form->addText('Acolor', 'Odkazy v textu: ', 6, 6)
                ->setAttribute('class', 'colorPicker')
                ->setDefaultValue($cssArray->Acolor);
        $form->addText('AcolorH', 'Odkazy v textu (hover): ', 6, 6)
                ->setAttribute('class', 'colorPicker')
                ->setDefaultValue($cssArray->AcolorH);
        $form->addText('ContentColor', 'Pozadí obsahu, levého menu, spodní ohraničení loga a horní ohraničení patičky: ', 6, 6)
                ->setAttribute('class', 'colorPicker')
                ->setDefaultValue($cssArray->ContentColor);
        $form->addText('pageColor', 'Pozadí layoutu, hlavičky, ohraničení příspěvků v knize návštěv: ', 6, 6)
                ->setAttribute('class', 'colorPicker')
                ->setDefaultValue($cssArray->pageColor);
        $form->addText('hColor', 'Nadpisy: ', 6, 6)
                ->setAttribute('class', 'colorPicker')
                ->setDefaultValue($cssArray->hColor);
        $form->addText('footer', 'Pozadí patičky, horního menu, záhlaví panelů levého menu, záhlaví příspěvků knihy návštěv: ', 6, 6)
                ->setAttribute('class', 'colorPicker')
                ->setDefaultValue($cssArray->footer);
        $form->addText('menuA', 'Odkazy hlavního menu, barva písma v patičce a písma v záhlaví panelů levého menu: ', 6, 6)
                ->setAttribute('class', 'colorPicker')
                ->setDefaultValue($cssArray->menuA);
        $form->addText('menuH', 'Odkazy hlavního menu (hover), zvýraznění aktuálního odkazu menu, barva odkazů v patičce: ', 6, 6)
                ->setAttribute('class', 'colorPicker')
                ->setDefaultValue($cssArray->menuH);

        $form->addGroup('Nastavení barev fotogalerie');
        $form->addText('galleryTd', 'Buňky náhledů fotogalerie: ', 6, 6)
                ->setAttribute('class', 'colorPicker')
                ->setDefaultValue($cssArray->galleryTd);
        $form->addText('galleryTdH', 'Buňky náhledů fotogalerie (hover): ', 6, 6)
                ->setAttribute('class', 'colorPicker')
                ->setDefaultValue($cssArray->galleryTdH);
        $form->addText('tdColor', 'Písmo u náhledů fotogalerie: ', 6, 6)
                ->setAttribute('class', 'colorPicker')
                ->setDefaultValue($cssArray->tdColor);


        $form->addSubmit('create', 'Uložit');

        $form->onSuccess[] = callback($this, 'setLayoutFormSubmitted');

        return $form;
    }

    public function setLayoutFormSubmitted(Form $form) {
        if (empty($form->values->float)) {
            $float = 'r';
        } else {
            $float = $form->values->float;
        }
        $this->model->getLayout()->update(array(
            'title' => $form->values->title,
            'description' => $form->values->description,
            'padding' => $form->values->padding,
            'layout' => $form->values->layout,
            'logo' => $form->values->logo,
            'float' => $float,
            'width' => $form->values->width,
            'backColor' => $form->values->color,
            'Acolor' => $form->values->Acolor,
            'AcolorH' => $form->values->AcolorH,
            'ContentColor' => $form->values->ContentColor,
            'pageColor' => $form->values->pageColor,
            'textColor' => $form->values->textColor,
            'hColor' => $form->values->hColor,
            'footer' => $form->values->footer,
            'menuA' => $form->values->menuA,
            'menuH' => $form->values->menuH,
            'galleryTd' => $form->values->galleryTd,
            'galleryTdH' => $form->values->galleryTdH,
            'tdColor' => $form->values->tdColor,
        ));
        $this->flashMessage('Vzhled byl nastaven.');
        $this->redirect('this');
    }

// nastavení pravého panelu
    public function handleVerejna($id) {
        if (isset($id)) {
            $this->model->getSettings()->where('id', $id)->update(array(
                'public' => '1'
            ));
            $this->flashMessage('Panel byl nastaven jako viditelný.');
            $this->redirect('this');
        } else {
            $this->model->getSettings()->insert(array(
                'select' => 'right_block',
                'public' => '1'
            ));
            $this->flashMessage('Panel byl nastaven jako viditelný.');
            $this->redirect('this');
        }
    }

// nastavení pravého panelu
    public function handleSkryta($id) {
        if (isset($id)) {
            $this->model->getSettings()->where('id', $id)->update(array(
                'public' => '0'
            ));
            $this->flashMessage('Panel byl nastaven jako skrytý.');
            $this->redirect('this');
        }
    }

// nastavení levého panelu
    public function handleLverejna($id) {
        if (isset($id)) {
            $this->model->getSettings()->where('id', $id)->update(array(
                'public' => '1'
            ));
            $this->flashMessage('Panel byl nastaven jako viditelný.');
            $this->redirect('this');
        } else {
            $this->model->getSettings()->insert(array(
                'select' => 'left_block',
                'public' => '1'
            ));
            $this->flashMessage('Panel byl nastaven jako viditelný.');
            $this->redirect('this');
        }
    }

// nastavení levého panelu
    public function handleLskryta($id) {
        if (isset($id)) {
            $this->model->getSettings()->where('id', $id)->update(array(
                'public' => '0'
            ));
            $this->flashMessage('Panel byl nastaven jako skrytý.');
            $this->redirect('this');
        }
    }

    // nastavení menu - nahoře
    public function handleTop($id) {
        if (isset($id)) {
            $this->model->getSettings()->where('id', $id)->update(array(
                'public' => '1'
            ));
            $this->flashMessage('Menu bylo nastaveno nahoru.');
            $this->redirect('this');
        } else {
            $this->model->getSettings()->insert(array(
                'select' => 'menu',
                'public' => '1'
            ));
            $this->flashMessage('Menu bylo nastaveno nahoru.');
            $this->redirect('this');
        }
    }

    // nastavení menu - dole
    public function handleLeft($id) {
        if (isset($id)) {
            $this->model->getSettings()->where('id', $id)->update(array(
                'public' => '0'
            ));
            $this->flashMessage('Menu bylo nastaveno vlevo.');
            $this->redirect('this');
        } else {
            $this->model->getSettings()->insert(array(
                'select' => 'menu',
                'public' => '0'
            ));
            $this->flashMessage('Menu bylo nastaveno vlevo.');
            $this->redirect('this');
        }
    }

    public function handleReset() {
        $this->model->getLayout()->delete();
        $this->flashMessage('Nastavení layoutu bylo resetováno do výchozího nastavení.');
        $this->redirect('this');
    }

}