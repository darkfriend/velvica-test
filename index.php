<?php
/**
 * Created by PhpStorm.
 * User: darkfriend <hi@darkfriend.ru>
 * Date: 26.02.2019
 * Time: 18:29
 */

require_once __DIR__.'/vendor/autoload.php';

\darkfriend\WordToWord::getInstance()
		->process('лужа','море')
		->show();