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

?>
<html>
    <head>
        <title>Blobble</title>
        <style type="text/css">
            body {
                font-family: Helvetica, Bitstream Vera Sans, sans-serif;
                color: #000000;
            }
            .content {
                text-align: left;
                margin-left: 20%;
                margin-top: 8%;
            }
            .heading {
                font-size: 3em;
                font-weight: bold;
            }
            A:link {
                text-decoration: none;
                color: #008000;
            }
            A:visited {
                text-decoration: none;
                color: #008000;
            }
            A:active {
                text-decoration: none;
                color: #008000;
            }
            A:hover {
                text-decoration: underline;
                color: #008000;
            }
        </style>
    </head>
    <body>
    <body>
        <div><a href="?logout=1">Not <?=htmlentities($aUser['name'])?>?</a></div>
        <div class="content">
<?
            if (isset($_GET['mac'])) {
                if (preg_match('/([a-fA-F0-9]+:?)+/', trim($_GET['mac']))) {
                    $oSession->setUserMac($user, trim($_GET['mac']));
                    $aUser = $oSession->getUserByName($user);
                } else {
                    print "Invalid MAC address!";
                }
            }
?>
            <div class="heading">Blobble</div>
            <div class="item">
                <form>
                    Your bluetooth Mac address:<br>
                    <input name="mac" type="text" value="<?=htmlentities($aUser['mac'])?>">
                    <input type="submit" value="Save MAC">
                </form>
            </div>
            <div class="item" style="width:40%; margin-top: 1%">
                <small>
                Once you've entered your bluetooth MAC address, songs you hear playing on Blobble enabled computers will automatically be scrobbled to your Last.fm profile.
                </small>
            </div>
        </div>
    </body>
</html>
<?


