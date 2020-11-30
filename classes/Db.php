<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////
//  GLORIA VERSION 1.1.0 - DATABASE CONNECTION
/////////////////////////////////////////////////////////////////////////////////////////////////////

class Db {
    protected static $connection;

    public function connect() {
        if(!isset(self::$connection)) {
            self::$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        }

        if(self::$connection === false) {
            return false;
        }
        return self::$con