<?php

namespace mj\libraries;

use mj\config;

class user{

    public static function getInstance(){
        
        $className = str_replace('\\', DIRECTORY_SEPARATOR, config::$classUser);

        if( file_exists(MJ_PATH . $className . '.php')){

            $user = new $className;

        } else {
            
            $user = new \stdClass;
            $user->ID = 0;
            $user->name = 'guest';
            $user->logged_at = date('Y-m-d H:i:s');
        }

        return $user;
    }
}