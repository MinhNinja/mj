<?php

namespace mj\libraries;

use mj\models\User as mUser;

class user{

    public function current(){
        $user = session::get('_user');
        if(empty($user)){
            $user = new \stdClass;
            $user->ID = 0;
            $user->name = 'guest';
            $user->logged_at = date('Y-m-d H:i:s');
            session::set('_user', $user);
        }
        return $user;
    }

    public function is( $key ){
        $user = $this->current();
        switch($key){
            case 'logged':
                return $user->ID;
                break;
        }
    } 
} 