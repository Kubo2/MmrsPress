<?php

/**
 * Základní třída modelu.
 */
class Model extends Nette\Object {

    /** @var Nette\Database\Connection */
    public $database;

    /**
     * @param Nette\Database\Connection $database
     */
    public function __construct(Nette\Database\Connection $database) {
        $this->database = $database;
    }

    public function isTables() {
        return $this->database->getSupplementalDriver()->getTables();
    }

    public function getInstall() {
        return $this->database->exec('
                    CREATE TABLE IF NOT EXISTS `mmrs_book` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `name` varchar(30) COLLATE utf8_bin NOT NULL,
                      `email` varchar(50) COLLATE utf8_bin NOT NULL,
                      `web` varchar(100) COLLATE utf8_bin NOT NULL,
                      `date` datetime NOT NULL,
                      `mesage` varchar(5000) COLLATE utf8_bin NOT NULL,
                      `avatar` varchar(50) COLLATE utf8_bin NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


                    CREATE TABLE IF NOT EXISTS `mmrs_counter` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `info` varchar(10000) COLLATE utf8_bin NOT NULL,
                      `date` date NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


                    CREATE TABLE IF NOT EXISTS `mmrs_counter_all` (
                      `count` int(11) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


                    CREATE TABLE IF NOT EXISTS `mmrs_gallery` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `name` varchar(35) COLLATE utf8_bin NOT NULL,
                      `label` varchar(500) COLLATE utf8_bin NOT NULL,
                      `folder` varchar(35) COLLATE utf8_bin NOT NULL,
                      `public` int(2) NOT NULL,
                      `autor` varchar(50) COLLATE utf8_bin NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


                    CREATE TABLE IF NOT EXISTS `mmrs_layoutSet` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `title` varchar(50) COLLATE utf8_bin NOT NULL,
                      `description` varchar(250) COLLATE utf8_bin NOT NULL,
                      `padding` int(11) NOT NULL,
                      `layout` varchar(30) COLLATE utf8_bin NOT NULL,
                      `logo` varchar(20) COLLATE utf8_bin NOT NULL,
                      `float` varchar(2) COLLATE utf8_bin NOT NULL,
                      `width` varchar(20) COLLATE utf8_bin NOT NULL,
                      `backColor` varchar(8) COLLATE utf8_bin NOT NULL,
                      `Acolor` varchar(8) COLLATE utf8_bin NOT NULL,
                      `ContentColor` varchar(8) COLLATE utf8_bin NOT NULL,
                      `AcolorH` varchar(8) COLLATE utf8_bin NOT NULL,
                      `pageColor` varchar(8) COLLATE utf8_bin NOT NULL,
                      `textColor` varchar(8) COLLATE utf8_bin NOT NULL,
                      `hColor` varchar(8) COLLATE utf8_bin NOT NULL,
                      `footer` varchar(8) COLLATE utf8_bin NOT NULL,
                      `menuA` varchar(8) COLLATE utf8_bin NOT NULL,
                      `menuH` varchar(8) COLLATE utf8_bin NOT NULL,
                      `galleryTd` varchar(8) COLLATE utf8_bin NOT NULL,
                      `galleryTdH` varchar(8) COLLATE utf8_bin NOT NULL,
                      `tdColor` varchar(8) COLLATE utf8_bin NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


                    CREATE TABLE IF NOT EXISTS `mmrs_news` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `content` text COLLATE utf8_bin NOT NULL,
                      `menu` varchar(30) COLLATE utf8_bin NOT NULL,
                      `date` datetime NOT NULL,
                      `autor` varchar(50) COLLATE utf8_bin NOT NULL,
                      `section` varchar(30) COLLATE utf8_bin NOT NULL,
                      `public` int(2) NOT NULL,
                      `publicDate` date NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


                    CREATE TABLE IF NOT EXISTS `mmrs_photos` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `label` varchar(300) COLLATE utf8_bin NOT NULL,
                      `photo` varchar(30) COLLATE utf8_bin NOT NULL,
                      `folder` varchar(35) COLLATE utf8_bin NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


                    CREATE TABLE IF NOT EXISTS `mmrs_section` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `section` varchar(100) COLLATE utf8_bin NOT NULL,
                      `public` int(2) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


                    CREATE TABLE IF NOT EXISTS `mmrs_setImg` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `thumbImg` int(11) NOT NULL,
                      `wiewImg` int(11) NOT NULL,
                      `newsImg` int(11) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


                    CREATE TABLE IF NOT EXISTS `mmrs_setRss` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `news` int(2) NOT NULL,
                      `pages` int(2) NOT NULL,
                      `book` int(2) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

                    CREATE TABLE IF NOT EXISTS `mmrs_settings` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `select` varchar(100) COLLATE utf8_bin NOT NULL,
                      `count` int(11) NOT NULL,
                      `public` int(2) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

                    CREATE TABLE IF NOT EXISTS `mmrs_users` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `users` varchar(30) COLLATE utf8_bin NOT NULL,
                      `password` varchar(120) COLLATE utf8_bin NOT NULL,
                      `web` varchar(100) COLLATE utf8_bin NOT NULL,
                      `email` varchar(120) COLLATE utf8_bin NOT NULL,
                      `role` int(2) DEFAULT NULL,
                      `avatar` varchar(50) COLLATE utf8_bin NOT NULL,
                      `active` varchar(32) COLLATE utf8_bin NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
            ');
        $subject = $_SERVER['HTTP_HOST'];
        $message = "Nový web - " . $_SERVER['HTTP_HOST'] . " \n";
        $headers = 'From:' . $_SERVER['SERVER_ADMIN '] . "\r\n" .
                'Reply-To:' . $_SERVER['SERVER_ADMIN '] . "\r\n" .
                'MIME-Version: 1.0' . "\n" . 'Content-type: text/plain; charset=UTF-8; Content-Transfer-Encoding: 8bit';
        mail('rellik@mmrspress.eu', $subject, strip_tags($message), $headers);
    }

    public function getBook() {
        return $this->database->table('mmrs_book');
    }

    public function getUsers() {
        return $this->database->table('mmrs_users');
    }

    public function getNews() {
        return $this->database->table('mmrs_news');
    }

    public function getGallery() {
        return $this->database->table('mmrs_gallery');
    }

    public function getPhotos() {
        return $this->database->table('mmrs_photos');
    }

    public function getSetImg() {
        return $this->database->table('mmrs_setImg');
    }

    public function getSetLayout() {
        return $this->database->table('mmrs_layout');
    }

    public function getLayout() {
        return $this->database->table('mmrs_layoutSet');
    }

    public function getRightBlock() {
        return $this->database->table('mmrs_rightBlock');
    }

    public function getCounter() {
        return $this->database->table('mmrs_counter');
    }

    public function getCounter_all() {
        return $this->database->table('mmrs_counter_all');
    }

    public function getSetRss() {
        return $this->database->table('mmrs_setRss');
    }

    public function getSettings() {
        return $this->database->table('mmrs_settings');
    }

    public function getSection() {
        return $this->database->table('mmrs_section');
    }

    public function getReset() {
        // set default colors
        $this->getLayout()->insert(array(
            'title' => 'MmrsPress',
            'description' => 'MmrsPress Jednoduchý redakční systém zdarma. Jednoduchá administrace a nastavení celého webu. Fotogalerie, kniha návštěv. Uživatelské účty.',
            'padding' => '150',
            'layout' => 'black',
            'logo' => 'mmrs',
            'float' => 'l',
            'width' => '80%',
            'backColor' => 'f0f0f0',
            'Acolor' => '4f4f4f',
            'AcolorH' => '000011',
            'ContentColor' => 'ffffff',
            'pageColor' => 'e3e3e3',
            'textColor' => '000000',
            'hColor' => '000000',
            'footer' => 'bdbdbd',
            'menuA' => '666666',
            'menuH' => '222222',
            'galleryTd' => 'dbdbdb',
            'galleryTdH' => 'c7c7c7',
            'tdColor' => '000000',
        ));
        // set default layout
        $this->getSettings()->insert(array(
            'select' => 'menu',
            'public' => '0',
        ));
        $this->getSettings()->insert(array(
            'select' => 'left_block',
            'public' => '1'
        ));
        $this->getSettings()->delete();
    }

}