<?php

    require_once('lib/php-last.fm-api/src/lastfm.api.php');
    require_once('lib/session.class.php');
    require_once('lib/scrobbler.class.php');

    $oSession = new Session();
    $oSession->createDb();

    $oCall = CallerFactory::getDefaultCaller();
    $oCall->setApiKey('feca1bb1c195dd2bcc6e331eca7696df');
    $oCall->setApiSecret('42505d22eef1a217f3f184fed2be882a');
