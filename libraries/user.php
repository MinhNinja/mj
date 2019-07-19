<?php

namespace mj\libraries;

use mj\config;

class user{

    public static function getInstance(){

        $className = 'mj\\models\\'.config::$classUser;
        if( class_exists($className, false) )
            return new $className;

        $user = new \stdClass;
        $user->ID = 0;
        $user->name = 'guest';
        $user->logged_at = date('Y-m-d H:i:s');

        return $user;
    }
}