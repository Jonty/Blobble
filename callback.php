<?php

    require_once('init.php');

    if (!isset($_REQUEST['token'])) {
        print "No token supplied!\n";
        exit;
    }

    $auth = (array) $oCall->signedCall('auth.getSession', array('token' => $_REQUEST['token']));
    $oSession->createUser($auth['name'], $auth['key']);
    setcookie('user', $oSession->generate($auth['name']));
    header('Location: index.php');
