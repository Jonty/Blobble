<?php

    class Scrobbler {
        const SUBS_URL = 'http://post.audioscrobbler.com/';

        private $curl;

        private $user;
        private $session;
        private $api_key;
        private $secret;

        private $suburl;
        private $scrobblesession;
        
        function __construct($user, $session, $api_key, $secret) {
            $this->user = $user;
            $this->session = $session;
            $this->api_key = $api_key;
            $this->secret = $secret;

            $this->handshake();
        }

        function initcurl() {
            $this->curl  = curl_init();
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($this->curl, CURLOPT_USERAGENT, 'Blobble -0.1');
        }

        function handshake() {
            $this->initcurl();

            $now = time();
            $query = http_build_query(
                array(
                    'hs'    => 'true',
                    'p'     => '1.2.1',
                    'c'     => 'tst',
                    'v'     => '1.0',
                    'u'     => $this->user,
                    't'     => $now,
                    'a'     => md5($this->secret . $now),
                    'api_key' => $this->api_key,
                    'sk'    => $this->session,
                ), '', '&'
            );

            curl_setopt($this->curl, CURLOPT_URL, self::SUBS_URL . '?' . $query);
            curl_setopt($this->curl, CURLOPT_POST, 0);

            $response = curl_exec($this->curl);
            $aLines = explode("\n", $response);

            if (count($aLines) == 5) {
                $this->scrobblesession = $aLines[1];
                $this->suburl = $aLines[3];
                return true;
            } else {
                return false;
            }
        }

        function scrobble ($artist, $track, $time, $album = '') {
            $this->initcurl();

            $now = time();
            $query = http_build_query(
                array(
                    's'     => $this->scrobblesession,
                    'a[0]'  => $artist,
                    't[0]'  => $track,
                    'i[0]'  => $time,
                    'o[0]'  => 'E',
                    'r[0]'  => '',
                    'l[0]'  => '',
                    'b[0]'  => $album,
                    'n[0]'  => '',
                    'm[0]'  => '',
                ), '', '&'
            );

            curl_setopt($this->curl, CURLOPT_URL, $this->suburl);
            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $query);

            $response = curl_exec($this->curl);
            return trim($response) == 'OK';
        }
    }
