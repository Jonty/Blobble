<?php

    class Session {
        const SECRET = 'badger';
        private $dbFile = 'sessions.db';
        private $db;

        function db($reinitialise = false) {
            if (!$this->db || $reinitialise) {
                $this->db = new PDO("sqlite:{$this->dbFile}");
            }
            return $this->db;
        }

        function createDb() {
            // Reinit the db to nuke the error that caused the create to occur
            $db = $this->db(true);

            $db->query(
                'CREATE TABLE users (
                    name string,
                    mac string,
                    key string,
                    PRIMARY KEY (name)
                );'
            );
        }

        function validate($session) {
            list($name, $hash) = explode('-', $session);
            return ($this->generate($name) === $session) ? $name : false;
        }

        function generate($name) {
            return $name.'-'.md5($name . self::SECRET);
        }

        function getUserByName($name) {
            $db = $this->db();

            $oUserFetch = $db->prepare(
                'SELECT * FROM users WHERE name = ?;'
            );

            // Create the table if it doesn't exist
            $oUserFetch->execute(
                array($name)
            );

            $aUserData = null;
            if ($aRows = $oUserFetch->fetchAll()) {
                $aUserData = $aRows[0];
            }

            return $aUserData;
        }

        function getUserByMac($mac) {
            $mac = strtolower($mac);
            $db = $this->db();

            $oUserFetch = $db->prepare(
                'SELECT * FROM users WHERE mac = ?;'
            );

            // Create the table if it doesn't exist
            $oUserFetch->execute(
                array($mac)
            );

            $aUserData = null;
            if ($aRows = $oUserFetch->fetchAll()) {
                $aUserData = $aRows[0];
            }

            return $aUserData;
        }

        function createUser($name, $key) {
            $db = $this->db();

            $oUserInsert = $db->prepare('
                INSERT INTO users
                    (name, key)
                VALUES
                    (?,?);'
            );

            $aUserData = array(
                $name, $key
            );

            return $oUserInsert->execute($aUserData);
        }

        function setUserMac($name, $mac) {
            $mac = strtolower($mac);
            $db = $this->db();

            $oUserUpdate = $db->prepare('
                UPDATE users SET mac = ? WHERE name = ?;'
            );

            $aUserData = array(
                $mac, $name
            );

            return $oUserUpdate->execute($aUserData);
        }

        function getMacs() {
            $db = $this->db();

            $oMacFetch = $db->prepare(
                'SELECT mac FROM users;'
            );

            // Create the table if it doesn't exist
            $oMacFetch->execute();

            $aMacData = null;
            if ($aRows = $oMacFetch->fetchAll()) {
                $aMacData = $aRows;
            }

            return $aMacData;
        }

    }
