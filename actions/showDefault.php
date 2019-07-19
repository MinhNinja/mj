<?php

namespace mj\actions;

use mj\libraries\application as App;

/**
 * if not logged, force to redirect before move to next action
 */
class showDefault extends action{

    public function prepareContent(){
        App::_('_content', $this->loadView('system'));
    }
}