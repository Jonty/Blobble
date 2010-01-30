<?php

    require_once('init.php');

    if (isset($_GET['user']) && isset($_GET['macs'])) {
        $aRecentData = (array) $oCall->call('user.getRecentTracks', array('user' => $_GET['user']));
        $aLast = (array) $aRecentData['track'][0];

        $aMacs = explode(',', $_GET['macs']);
        foreach ($aMacs as $mac) {
            $aUser = $oSession->getUserByMac($mac);
            if ($aUser) {
                $oScrob = new Scrobbler($aUser['name'], $aUser['key'], $oCall->getApiKey(), $oCall->getApiSecret());
                $oScrob->scrobble($aLast['artist'], $aLast['name'], strtotime($aLast['date']));
            }
        }
    }

    print "OK";
