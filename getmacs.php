<?php

    require_once('init.php');

    $aMacs = $oSession->getMacs();
    foreach ($aMacs as $mac) {
        print $mac[0]."\n";
    }
