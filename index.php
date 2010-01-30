<?php

    require_once('init.php');

    if (!isset($_COOKIE['user']) || !$oSession->validate($_COOKIE['user'])) {
        header('Location: http://www.last.fm/api/auth/?api_key=' . $oCall->getApiKey());
        exit;
    }

    $user = $oSession->validate($_COOKIE['user']);
    $aUser = $oSession->getUserByName($user);

    if (!$aUser || isset($_GET['logout'])) {
        setcookie('user', '');
        header('Location: index.php');
        exit;
    }

    if (isset($_GET['mac'])) {
        if (preg_match('/([\w\d]+:?)+/', trim($_GET['mac']))) {
            $oSession->setUserMac($user, trim($_GET['mac']));
            print "<h4>MAC set</h4><hr>";
        } else {
            print "<h4>Invalid MAC!</h4><hr>";
        }
    }

    print "Blobble! Logged in as '$user' (<a href='?logout=1'>logout</a>)<br><br>";
    print "Your phone bluetooth MAC address: <form><input name='mac' type='text' value='".htmlentities($aUser['mac'])."'><input type='submit' value='Save MAC'></form>";
