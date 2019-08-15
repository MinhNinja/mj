<?php

namespace mj\actions;

use mj\libraries\application as App;
use mj\libraries\action;

/**
 * process before main Action
 */
class preparePostInWidget extends action{

    public function processLogic(){
        
        $infoText = App::use('input')->get('infoText', 'cmd');
        $infoDate = App::use('input')->get('infoDate', 'date');

        $this->vars( compact('infoText', 'infoDate') );
    }
}